<?php

namespace App\Services\Security;

use App\Models\UserActivityLog;
use Illuminate\Support\Facades\Auth;

class ActivityLogger
{
    public function log(string $action, array $meta = []): void
    {
        UserActivityLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'ip_address' => request()->ip(),
            'meta' => $meta,
        ]);
    }
}
