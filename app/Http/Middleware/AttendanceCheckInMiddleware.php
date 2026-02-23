<?php

namespace App\Http\Middleware;

use App\Models\AttendanceLog;
use Closure;
use Illuminate\Http\Request;

class AttendanceCheckInMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if ($user && ! $user->attendanceLogs()->where('status', 'Active')->exists()) {
            AttendanceLog::create([
                'user_id' => $user->id,
                'check_in_time' => now(),
                'status' => 'Active',
            ]);
        }

        return $next($request);
    }
}
