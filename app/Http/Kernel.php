<?php

namespace App\Http;

use App\Http\Middleware\EnforceReadOnlyConnections;
use App\Http\Middleware\LogUserActivity;
use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $middleware = [
        EnforceReadOnlyConnections::class,
    ];

    protected $middlewareGroups = [
        'web' => [
            LogUserActivity::class,
            EnforceReadOnlyConnections::class,
        ],
    ];

    protected $routeMiddleware = [
        'readonly.db' => EnforceReadOnlyConnections::class,
        'activity.log' => LogUserActivity::class,
    ];
}
