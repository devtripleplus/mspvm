<?php namespace App\Http\Controllers;

use App\Http\Controllers\Auth\AuthController;
use App\User;
use Illuminate\Http\Request;

Class AuthenticationController extends AuthController  {
    protected $loginView = 'login';

    protected $username = 'username';

    /**
     * Get the failed login response instance.
     *
     * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        return redirect()->back()
            ->withInput($request->only($this->loginUsername(), 'remember'))
            ->withErrors($this->getFailedLoginMessage());
    }

    public function authenticated(Request $request, User $user) {
        if ($user->access_level != 1) {
            return redirect()->route('admin.home')->withMessage('Welcome back!');
        } else {
            return redirect()->route('vms')->withMessage('Welcome back!');
        }
    }
}