<?php namespace App\Http\Controllers;

use App\VM;
Use App\Log;
Use App\IP;
Use App\Backup;

Class ClientVMController extends Controller  {
    public function my_vms() {
        return view('client/vms')
            ->with('servers', $this->user->servers()->get())
            ->with('title', 'My VMs');
    }

    public function manage($vm_id) {
        $vm = VM::find($vm_id);

        if (!$vm->exists || $vm->user_id != $this->user->id) {
            return redirect()->back()->withErrors('You are not allowed to manage this VM!');
        }

        return view('client/vm')
            ->with('title', $vm->getName())
            ->with('vm', $vm)
            ->with('logs', Log::where('vps_id', '=', $vm->id)->orderBy('created_at', 'DESC')->limit(15)->get())
            ->with('controls', app('control')->getVMControls($vm, 1))
            ->with('ips', IP::where('vps_id', '=', $vm->id)->get())
            ->with('available_ips', IP::where('vps_id', '=', '0')->lists('ip_address', 'id')->toArray())
            ->with('backup_methods', app('backup'))
            ->with('backups', Backup::where('vps_id', '=', $vm->id)->orderBy('id', 'DESC')->get());
    }
}