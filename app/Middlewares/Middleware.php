<?php

namespace App\Middlewares;

interface Middleware
{
    function next($request, callable $next);
}
