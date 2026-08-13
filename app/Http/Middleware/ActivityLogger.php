<?php

namespace App\Http\Middleware;

use App\Models\ActivityLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ActivityLogger
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($request->user()) {
            ActivityLog::create([
                'user_id' => $request->user()->id,
                'action' => $request->method() . ' ' . $request->path(),
                'entity_type' => null,
                'entity_id' => null,
                'ip_address' => $request->ip(),
                'metadata' => ['status' => $response->getStatusCode()],
            ]);
        }

        return $response;
    }
}
