<?php

namespace Bishopm\Church\Http\Middleware;

use Closure;
use Illuminate\Http\Response;

class GivingRoute
{
    public function handle($request, Closure $next)
    {
        if (!$this->isGiving($request)) {
            abort(Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }

    protected function isGiving($request)
    {
        return $request->user()->hasRole('Finance');
    }
}