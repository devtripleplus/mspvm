<?php namespace App\Http\Controllers;

use App\Controls\DeleteVMControl;
use App\Http\Requests\UserRequest;
use App\Log;
use App\ResourcePool;
use App\Server;
use App\User;
use App\VM;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

Class AdminUserController extends Controller  {
    public function users() {
        $extra = [];

        if ($this->request->get('type')) {
            $extra = [
                'type' => $this->request->get('type')
            ];
        }

        return view('admin/users')
            ->with('extra', $extra)
            ->with('title', 'Users');
    }

    public function user($user_id) {
        $user = User::find($user_id);

        return view('admin/user')
            ->with('theuser', $user)
            ->with('vms', VM::where('user_id', '=', $user_id)->get())
            ->with('title', $user->username);
    }

    public function doUpdate($user_id) {
        $user = User::find($user_id);

        $user->update([
            'password' => Hash::make($this->request->input('password'))
        ]);

        return redirect()->back()->withMessage('Password updated!');
    }

    public function create() {
        return view('admin/user_create')
            ->with('title', 'New User');
    }

    public function doAdminLogin($user_id) {
        $user = User::find($user_id);

        if (!$user) {
            return redirect()->back()->withErrors([
               'Invalid user!'
            ]);
        }

        if ($user->id == $this->user->id) {
            return redirect()->back()->withErrors([
               'You can\'t log in as yourself!'
            ]);
        }

        Session::put('admin_user_id', $this->user->id);

        if (!Auth::loginUsingId($user_id)) {
            Session::put('admin_user_id', null);
        }

        return redirect()->route('home');
    }

    public function doDelete($user_id) {
        $user = User::find($user_id);

        if (!$user) {
            return redirect()->back()->withErrors([
                'Invalid user!'
            ]);
        }

        if (VM::where('user_id', '=', $user->id)->count()) {
            return redirect()->back()->withErrors([
                'The user has active VMs!'
            ]);
        }

        if (ResourcePool::where('user_id', '=', $user->id)->count()) {
            return redirect()->back()->withErrors([
                'The user has active resource pools!'
            ]);
        }

        $user->delete();

        return redirect()->route('admin.users')->withMessage('The user bas been deleted!');
    }

    public function doPurge($user_id, DeleteVMControl $control) {
        $user = User::find($user_id);

        if (!$user) {
            return redirect()->back()->withErrors([
                'Invalid user!'
            ]);
        }

        // delete VMs
        foreach (VM::where('user_id', '=', $user->id)->get() as $vm) {
            $logEnry = '';

            $control->execute($vm, $logEntry);
        }

        // delete resource pools
        foreach (ResourcePool::where('user_id', '=', $user->id)->get() as $rp) {
            $rp->delete();
        }

        $user->delete();

        return redirect()->route('admin.users')->withMessage('The user bas been deleted!');
    }

    public function doCreate(UserRequest $request) {

        $user = User::create(array_merge($request->only(
            'username',
            'email_address',
            'access_level'
        ), [
            'password' => Hash::make($request->input('password'))
        ]));

        return redirect()->route('admin.user', ['user_id' => $user->id])->withMessage('User created!');
    }
}