<?php

namespace Bishopm\Church\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!isset($_COOKIE['wmc-mobile']) or (!isset($_COOKIE['wmc-access']))){
            return redirect('/app/login');
        }

        return $next($request);
    }
}
