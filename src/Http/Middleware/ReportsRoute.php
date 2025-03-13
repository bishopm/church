<?php

namespace Bishopm\Church\Http\Middleware;

use Closure;
use Illuminate\Http\Response;

class ReportsRoute
{
    public function handle($request, Closure $next)
    {
        if (!$this->isReportable($request)) {
            abort(Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }

    protected function isReportable($request)
    {
        if ($request->user()){
            return $request->user()->hasRole(['Super Admin','Office','Pastoral','Finance','HR']);
        } else {
            return false;
        }
    }
}