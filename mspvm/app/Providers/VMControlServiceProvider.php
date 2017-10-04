<?php

namespace App\Providers;

use App\Controls\RebootVMControl;
use App\Controls\VMControl;
use App\Driver\ControlDriver;
use App\Log;
use App\VM;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;

class VMControlServiceProvider extends ServiceProvider
{
    private $controls = [];

    /**
     *
     * @TODO ENFORCE USERID
     * @param Dispatcher $events
     */
    public function boot(Dispatcher $events)
    {
        $this->app->bind('control', ControlDriver::class);

        $this->controls = $this->app['control']->getControls();

        $this->app['router']->get('vmc/{vm_id}/', function ($vm_id) {
            if (Auth::guest()) {
                return redirect()->route('login');
            }

            $vm = VM::find($vm_id);

            $control = $this->getControl(request('c'));

            if (!$control) {
                return redirect()->back()->withErrors('Invalid control!');
            }

            if (!$vm || Auth::user()->access_level < $control->getlevel()) {
                return redirect()->back()->withErrors('You are not allowed to manage this VM!');
            }

            if ($control->hasForm($vm)) {
                return view('admin/vm_control', [
                    'control' => $control,
                    'title' => $control->getName(),
                    'vm' => $vm,
                    'user' => \Auth::user()
                ]);
            }

            $entry = 'The following action has been executed: '.$control->getName();

            if ($control) {
                $return = $control->execute($vm, $entry);
            }

            Log::create([
                'vps_id' => $vm->id,
                'entry' => $entry
            ]);

            if ($return == null) {
                return redirect()->back()->withMessage('The command has been queued!');
            }

            return $return;
        });

        $this->app['router']->post('vmc/{vm_id}/', function ($vm_id) {
            if (Auth::guest()) {
                return redirect()->route('login');
            }

            $vm = VM::find($vm_id);

            $control = $this->getControl(request('c'));

            if (!$control) {
                return redirect()->back()->withErrors('Invalid control!');
            }

            if (!$vm || Auth::user()->access_level < $control->getLevel()) {
                return redirect()->back()->withErrors('You are not allowed to manage this VM!');
            }

            if (!$control->hasForm($vm)) {
                throw new MethodNotAllowedException([]);
            }

            $errors = $control->validateData(app('request'));

            if (!empty($errors)) {
                return redirect()->to('vmc/'.$vm->id.'?c='.$control->getSlug())->withInput()->withErrors($errors);
            }

            $entry = 'The following action has been executed: '.$control->getName();

            if ($control) {
                $return = $control->execute($vm, $entry, app('request')->all());
            }

            Log::create([
                'vps_id' => $vm->id,
                'entry' => $entry
            ]);

            if ($return == null) {
                return redirect()->route('admin.vm', ['vm_id' => $vm->id])->withMessage('The command has been queued!');
            }

            return $return;
        });
    }

    /**
     * @param $slug
     * @return VMControl|null
     */
    private function getControl($slug) {
        foreach ($this->controls as $control) {
            if ($control->getSlug() == $slug) {
                return $control;
            }
        }

        return null;
    }
}
