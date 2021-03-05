<?php

namespace App\Http\Middleware;

use Closure;
use Session;

class Locale
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
        
        if(Session::has('website_language'))
        {
            app()->setlocale(Session::get('website_language'));
        }
        return $next($request);
    }
}
