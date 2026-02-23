<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class AuditLogger
{
    public function log(string $event, array $context = []): void
    {
        Log::channel('stack')->info("AUDIT_EVENT: {$event}", $context);
    }
}
