<?php

namespace App\Http\Middleware;

use Closure;
use Route;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

class CheckAdminLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $isLogin = false;
        try{
            $isLogin = Sentinel::check();
        }catch (\Exception $e){

        }

        if ($isLogin) {
            return redirect(url('backend/dashboard'));
        }

        return $next($request);
    }
}
