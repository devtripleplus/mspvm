<?php namespace App\Http\Controllers;

use App\Http\Requests\TemplateRequest;
use App\Server;
use App\Template;
use App\TemplateDeployment;
use Illuminate\Support\Str;

Class AdminTemplateController extends Controller  {
    public function templates() {
        return view('admin/templates')
            ->with('templates', Template::all())
            ->with('title', 'Template');
    }

    private function isBeingDeployed(Server $server, Template $template) {
        $sem_id = sem_get($template->id*10000+$server->id);

        if (!sem_acquire($sem_id, true)) {
            return true;
        }

        sem_release($sem_id);

        return false;
    }

    public function doDeploy($template_id, $server_id) {
        $template = Template::find($template_id);

        $server = Server::find($server_id);

        if (!$template || !$server) {
            return redirect()->back()->withError('Invalid data!');
        }

        $deployed = TemplateDeployment::where('template_id', '=', $template->id)->get();

        $found = count(array_filter($deployed->toArray(), function ($deployed) use($server) {
                return $deployed['id'] == $server->id;
            })) != 0;

        if (!$found) {
            $sem_id = sem_get($template->id*10000+$server->id);

            if (!sem_acquire($sem_id, true)) {
                return redirect()->back()->withError('Deployment already in progress!');
            }

            $error = '';

            // Deploy
            try {
                if ($server->virtualization()->deploy_template($template, $error)) {
                    $dep = TemplateDeployment::create([
                        'server_id' => $server->id,
                        'template_id' => $template->id,
                        'status' => 1
                    ]);
                }
            } catch (\ErrorException $e) {
                return redirect()->back()->withError($e->getMessage());
            }

            sem_release($sem_id);
        }

        return redirect()->back()->withMessage('Template deployed!');
    }

    public function template($template_id) {
        $template = Template::find($template_id);

        if (!$template) {
            return redirect()->back()->withErrors([
               'Invalid template!'
            ]);
        }

        $deployment = [];

        foreach (Server::all() as $server) {
            $dep = TemplateDeployment::where('server_id', '=', $server->id)->where('template_id', '=', $template->id)->first();

            $deployment[] = [
              'server' => $server,
                'deployed' => !is_null($dep) && $dep->status == 1,
                'deploying' => !is_null($dep) && $dep->status = 0 &&  $this->isBeingDeployed($server, $template)
            ];
        }

        return view('admin/template')
            ->with('template', $template)
            ->with('deployment', $deployment)
            ->with('title', $template->name);
    }

    public function create() {
        return view('admin/template_create')
            ->with('title', 'New Template');
    }

    private function getExtension($file) {
        $file = substr($file, -8);

        $file = explode(".", $file);

        if (count($file) == 3) {
            unset($file[0]);
        }

        return implode(".", $file);
    }

    /**
     * @TODO Delete deployment from remote server
     *
     * @param TemplateDeployment $dep
     */
    private function deleteDeployment(TemplateDeployment $dep) {
        $template = $dep->template();

        $dep->server()->virtualization()->undeploy_template($template);
    }

    public function doDelete($template_id) {
        $template = Template::find($template_id);

        if (!$template) {
            return redirect()->back()->withErrors([
                'Invalid template!'
            ]);
        }

        foreach (Server::all() as $server) {
            if ($this->isBeingDeployed($server, $template)) {
                return redirect()->back()->withErrors([
                    'You cannot delete a template that is being deployed!'
                ]);
            }
        }

        foreach (Server::all() as $server) {
            $dep = TemplateDeployment::where('server_id', '=', $server->id)->where('template_id', '=', $template->id)->first();

            if ($dep) {
                $this->deleteDeployment($dep);

                $dep->delete();
            }
        }

        $template->delete();

        return redirect()->route('admin.templates')->withMessage('The template has been deleted!');
    }

    public function doCreate(TemplateRequest $request) {
        $filename = Str::slug($request->input('name')).'_'.get_friendly_arc_name($request->input('architecture')).'.'.$this->getExtension($request->input('file'));

        $error = exec('wget -O /var/mspvm/templates/'.strtolower(Server::$virtualization[$request->input('type')]).'/'.$filename.' '.$request->input('file'));

        if ($error) {
            return redirect()->back()->withErrors([
                'wget failed with error '.$error
            ]);
        }

        $template = Template::create(array_merge($request->only(
            'name',
            'type',
            'architecture',
            'description'
        ), [
            'path' => $filename,
            'size' => 0,
            'disabled' => 0
        ]));

        return redirect()->route('admin.template', ['template_id' => $template->id])->withMessage('Template added!');
    }
}