<?php

namespace App\Services;

use App\Models\AttendanceLog;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class AttendanceService
{
    public function checkOut(User $user): AttendanceLog
    {
        $active = $user->attendanceLogs()->where('status', 'Active')->latest()->first();

        if (! $active) {
            throw ValidationException::withMessages([
                'attendance' => 'No active session found for checkout.',
            ]);
        }

        $active->update([
            'check_out_time' => now(),
            'status' => 'Closed',
        ]);

        return $active;
    }
}
