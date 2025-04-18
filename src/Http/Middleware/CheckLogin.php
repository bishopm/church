<?php

namespace Bishopm\Church\Http\Middleware;

use Bishopm\Church\Models\Individual;
use Bishopm\Church\Models\Pastor;
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
            } elseif (($route->getName() == 'app.pastoral') or ($route->getName() == 'app.pastoralcase')){
                $pastor=Pastor::where('individual_id',$_COOKIE['wmc-id'])->get();
                if (!count($pastor)){
                    return redirect(route('app.home'));
                }
            }
        } else {
            if (isset($_COOKIE['wmc-mobile']) and (isset($_COOKIE['wmc-access']))){
                $indiv=Individual::where('uid',$_COOKIE['wmc-access'])->where('cellphone',$_COOKIE['wmc-mobile'])->first();
                if ($indiv){
                    return redirect(route('app.home'));
                } else {
                    return $next($request);            
                }
            }
        }
        return $next($request);
    }
}
