<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;

use Closure;

class CheckAdmin
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
        if(Auth::check())
        {   
            $user = Auth::user();
            if($user->us_type == "1")
            {
                return $next($request);
            }
            else
            {
                return redirect()->route('login')->with("error","Bạn không phải Admin");
            }
        }
        else
        {
            return redirect()->route('login')->with("error","Bạn phải đăng nhập trước");
        }
    }
}
