<?php

namespace Bishopm\Church\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Route;

class CheckLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $route = Route::getRoutes()->match($request);
        if ($route->getName() <> 'app.login'){
            if (!isset($_COOKIE['wmc-mobile']) or (!isset($_COOKIE['wmc-access']))){
                return redirect(route('app.login'));
            }
        }
        return $next($request);
        /* $response=$next($request);
        $response->headers->set('Cache-Control', '');
        return $response; */
    }
}
