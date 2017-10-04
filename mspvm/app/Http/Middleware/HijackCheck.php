<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class HijackCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {

        if (app('auth')->check()) {
            $user = app('auth')->user();

            if (($user->access_level == 1 && settings('client-hijack-check')) || ($user->access_level == 3 && settings('admin-hijack-check'))) {
                // browser hijack
                if (in_array(settings('hijack-check-type'), [1,3]) && app('session')->get('session_browser')) {
                    if (app('session')->get('session_browser') != $request->server('HTTP_USER_AGENT')) {
                        return $this->handleHijackAttempt($request, $next);
                    }
                } else {
                    app('session')->put('session_browser', $request->server('HTTP_USER_AGENT'));
                }

                // ip hijack check
                if (in_array(settings('hijack-check-type'), [1,2]) && app('session')->get('session_ip')) {
                    if (app('session')->get('session_ip') != $request->ip()) {
                        return $this->handleHijackAttempt($request, $next);
                    }
                } else {
                    app('session')->put('session_ip', $request->ip());
                }
            }
        }

        return $next($request);
    }

    /**
     * @return \Illuminate\Http\Response
     */
    private function handleHijackAttempt($request, $next) {
        if (app('session')->get('hijack_redirect')) {
            app('session')->set('hijack_redirect', 0);
            return $next($request);
        }

        app('session')->flush();

        return redirect()->home()->withErrors([
            'Session hijack attempt blocked! You have been logged out!'
        ])->with('hijack_redirect', 1);
    }
}
