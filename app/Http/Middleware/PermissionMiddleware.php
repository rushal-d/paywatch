<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Session::get('role')===null) {
            session(
                [
                    'role' => Auth::user()->hasRole('Administrator'),
                    'isEmployee' => Auth::user()->hasRole('Employee')
                ]
            );
        }

        if (env('APP_DEBUG')) {
            return $next($request);
        }
        $user = Auth::user();
        $permission = \Illuminate\Support\Facades\Route::currentRouteName();

        if ($user->can($permission)) {
            return $next($request);
        } else {
            $status = 'error';
            $mesg = 'You Dont Have Permission';
            return redirect()->back()->with('flash', array('status' => $status, 'mesg' => $mesg));
        }


    }
}
