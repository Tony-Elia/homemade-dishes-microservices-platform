<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role)
    {
        if (auth()->check() && auth()->user()->role === $role) {
            return $next($request);
        }

        abort(403, 'Unauthorized');
    }
}
