<?php

namespace App\Http\Middleware;

use App\Services\Security\ActivityLogger;
use Closure;

class LogUserActivity
{
    public function __construct(private readonly ActivityLogger $activityLogger)
    {
    }

    public function handle($request, Closure $next)
    {
        if ($request->user()) {
            $this->activityLogger->log('request', [
                'method' => $request->method(),
                'path' => $request->path(),
            ]);
        }

        return $next($request);
    }
}
