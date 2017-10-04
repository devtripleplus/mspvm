<?php

namespace App\Http\Controllers;

use App\Option;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Controller extends BaseController
{
    use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;

    /**
     * @var \Illuminate\Http\Request
     */
    public $request;

    public function __construct() {
        $this->user = Auth::user();

        $this->request = app('request');

        View::share('user', $this->user);

        if (Session::get('admin_user_id')) {
            View::share('topmessage', '<div class="alert alert-warning">You are logged in as '.$this->user->username.'! <a href="'.route('admin.restore-session').'" class="pull-right">Restore admin session</a></div>');
        } else {
            View::share('topmessage', '');
        }

        foreach (Option::all() as $option) {
            config([
               'settings.'.$option->name => $option->value
            ]);
        }
    }

    public function restoreAdminSession() {
        $admin_user_id = Session::get('admin_user_id');

        if ($admin_user_id && Auth::loginUsingId($admin_user_id)) {
            Session::remove('admin_user_id');
            return redirect()->route('admin.home');
        }

        throw new NotFoundHttpException;
    }
}
