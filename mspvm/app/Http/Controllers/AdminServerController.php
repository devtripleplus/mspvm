<?php namespace App\Http\Controllers;

use App\Controls\DeleteVMControl;
use App\Http\Requests\ServerRequest;
use App\Server;
use App\Virtualization\OpenVZ;
use App\Virtualization\OpenVZServer;
use App\VM;

Class AdminServerController extends Controller  {
    public function servers() {
        return view('admin/servers')
            ->with('servers', Server::all())
            ->with('title', 'Servers');
    }

    public function server($server_id) {
        $server = Server::find($server_id);

        if (!$server) {
            return redirect()->back()->withErrors([
               'Invalid server!'
            ]);
        }

        return view('admin/server')
            ->with('server', $server)
            ->with('title', $server->name);
    }

    public function doDelete($server_id) {
        $server = Server::find($server_id);

        if (!$server) {
            return redirect()->back()->withErrors([
                'Invalid server!'
            ]);
        }

        if ($server->vmCount()) {
            return redirect()->back()->withErrors([
               'Unable to delete server with VMs on it!'
            ]);
        }

        $server->delete();

        return redirect()->back()->withMessage('The server has been deleted!');
    }

    public function doPurge($server_id, DeleteVMControl $control) {
        $server = Server::find($server_id);

        if (!$server) {
            return redirect()->back()->withErrors([
                'Invalid server!'
            ]);
        }

        foreach (VM::where('server_id', '=', $server_id) as $vm) {
            $logEnry = '';

            $control->execute($vm, $logEntry);
        }

        $server->delete();

        return redirect()->back()->withMessage('The server has been deleted!');
    }

    public function getStats($server_id) {
        $server = Server::find($server_id);

        if (!$server->stats()->isAvailable()) {
            throw new \Exception;
        }

        return view('admin/server/ajax_stats', [
            'server' => $server
        ]);
    }

    public function create() {
        return view('admin/server_create')
            ->with('title', 'New Server');
    }

    /**
     * @TODO Move salt as ENV option
     * @TODO update ServerRequest to ensure key is set
     *
     * @param ServerRequest $request
     * @return $this
     */
    public function doCreate(ServerRequest $request) {

        $server = Server::create($request->only(
            'name',
            'ip',
            'user'
        ));

        switch ($request->input('type')) {
            case 1:
                // keyfile auth
                $request->file('file')->move('/var/mspvm/keys/', sha1($server . 'asd123cvcsdhfgasdgds'));

                break;
            case 2:
                // basic auth
                // a keyfile is generated and downloaded
                $openVz = new OpenVZ($server);

                try {
                    $openVz->setup($request->all());
                } catch (\Exception $e) {
                    return redirect()->back()->withErrors($e->getMessage())->withInput();
                }
        }

        return redirect()->route('admin.server', ['server_id' => $server->id])->withMessage('Server registered!');
    }
}