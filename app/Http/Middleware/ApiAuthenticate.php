<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiAuthenticate
{

    public function handle(Request $request, Closure $next,...$guard)
    {
        dd($guard);
        return $next($request);
    }
}
