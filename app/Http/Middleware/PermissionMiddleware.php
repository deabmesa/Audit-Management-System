<?php

namespace App\Http\Middleware;

use Closure;

class PermissionMiddleware
{
    public function handle($request, Closure $next, $permission)
    {
        if (!auth()->check() || !auth()->user()->hasPermission($permission)) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}