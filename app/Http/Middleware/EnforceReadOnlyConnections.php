<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Database\Connection;
use Illuminate\Support\Facades\DB;

class EnforceReadOnlyConnections
{
    public function handle($request, Closure $next)
    {
        $this->lockConnection('external_pgsql');
        $this->lockConnection('external_oracle');

        return $next($request);
    }

    private function lockConnection(string $name): void
    {
        /** @var Connection $connection */
        $connection = DB::connection($name);

        $connection->beforeExecuting(function (string $query) use ($name) {
            if (preg_match('/^\s*(insert|update|delete|alter|drop|create|truncate)/i', $query)) {
                abort(403, "Write query attempted on read-only connection [{$name}].");
            }
        });
    }
}
