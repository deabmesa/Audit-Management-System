<?php

namespace App\Http\Controllers;

use App\Models\UserActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function checkIn(Request $request)
    {
        $latest = $this->latestAttendanceActivity(Auth::id());

        if ($latest === 'Checked in') {
            return back()->withErrors(['attendance' => 'You are already checked in.']);
        }

        UserActivityLog::create([
            'user_id' => Auth::id(),
            'activity' => 'Checked in',
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Check-in recorded successfully.');
    }

    public function checkOut(Request $request)
    {
        $latest = $this->latestAttendanceActivity(Auth::id());

        if ($latest !== 'Checked in') {
            return back()->withErrors(['attendance' => 'You must check in first before checking out.']);
        }

        UserActivityLog::create([
            'user_id' => Auth::id(),
            'activity' => 'Checked out',
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Check-out recorded successfully.');
    }

    private function latestAttendanceActivity(int $userId): ?string
    {
        return UserActivityLog::where('user_id', $userId)
            ->whereIn('activity', ['Checked in', 'Checked out'])
            ->latest('id')
            ->value('activity');
    }
}
