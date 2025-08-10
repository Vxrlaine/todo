<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Authenticate
{
    public function handle(Request $request, Closure $next, ...$guards)
    {
        if ($request->bearerToken() && !$request->hasHeader('device_name')) {
            $request->headers->set('device_name', 'default_device');
        }

        return $next($request);
    }
}