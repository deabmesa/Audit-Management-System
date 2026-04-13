<?php

namespace App\Http\Middleware;

use App\Models\FieldPermission;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckFieldPermission
{
    public function handle(Request $request, Closure $next, string $model, string $field, string $access = 'view'): Response
    {
        $user = $request->user();
        $allowed = FieldPermission::where('model', $model)
            ->where('field', $field)
            ->where('role_name', $user->getRoleNames()->first())
            ->where($access === 'edit' ? 'can_edit' : 'can_view', true)
            ->exists();

        abort_unless($allowed, 403);
        return $next($request);
    }
}
