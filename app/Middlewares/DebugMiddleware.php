<?php

namespace App\Middlewares;

use App\Requests\DebugRequest;

class DebugMiddleware implements Middleware
{

    function next($request, callable $next)
    {
        $debugRequest = new DebugRequest($request);
        $debugRequest->validate();

        $next($debugRequest);
    }
}
