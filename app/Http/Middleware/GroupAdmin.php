<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

class GroupAdmin
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

        if($user->group_id != 1){
            alertNotify(false,"Sorry, You are not authorize to open that page, contact admin for help", $request);
            return redirect(url('backend/shipping-partner'));
        }


        return $next($request);
    }
}
