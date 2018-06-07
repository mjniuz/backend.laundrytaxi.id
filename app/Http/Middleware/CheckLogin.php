<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class CheckLogin
{
    public function handle($request, Closure $next)
    {
        // Check hasLogin with email and password
        $isLogin    = Auth::check();
        if (!$isLogin) {

            return redirect(url('login'));
        }

        // Check status is active
        if (Auth::user()->status == 0) {
            return redirect(url('profile-user'));
        }

        // Check role is com
        if (Auth::user()->site_role == 'org')  {
            //return 'ente salah kamar';
        }

        // Check user has expired
        $now        = strtotime(date('Y-m-d H:i:s'));
        $expired    = strtotime(Auth::user()->com_expired_at);
        if ($now >= $expired) {
            //return 'bayar sek bro';
        }

        return $next($request);
    }
}