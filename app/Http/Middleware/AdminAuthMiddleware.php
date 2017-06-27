<?php

namespace App\Http\Middleware;

use App\Helpers\Helper;
use Closure;
use Illuminate\Support\Facades\Auth;

class AdminAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::guard('admin')->guest()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest(route('admin.login'));
            }
        }
        $user = Auth::guard('admin')->user();
        if (isset($user->role_id) && $user->role_id > 1) {
            $name = \Route::current()->uri;
            $action_lists = Helper::leftMenu('admin');
            $permission = Helper::actionUri($action_lists);
            $base = ['admin/home', 'admin/logout'];
            $permission = array_merge($base, $permission);
            if (!in_array($name, $permission)) {
                return abort(403);
            }
        }
//        die;
        return $next($request);
    }
}
