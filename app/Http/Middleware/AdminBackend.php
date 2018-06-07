<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

class AdminBackend
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    public function handle($request, Closure $next, $guard = null)
    {
        $user   = Sentinel::check();
        if(!$user) {
            return redirect(url('backend/login'));
        }

        $action = $request->route()->action;
        $as     = !empty($action['as']) ? $action['as'] : null;

        if(!$user->hasAccess($as . "*")) {
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            } else {
                return abort(401, "Unauthorized");
            }
        }

        return $next($request);
    }
}
