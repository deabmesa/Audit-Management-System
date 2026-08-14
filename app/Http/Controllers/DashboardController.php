<?php

namespace App\Http\Controllers;

use App\Models\Audit;
use App\Models\AttendanceLog;
use App\Models\Finding;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        return view('dashboard.index', [
            'activeAttendance' => $user->attendanceLogs()->where('status', 'Active')->latest()->first(),
            'auditStats' => [
                'total' => Audit::count(),
                'in_progress' => Audit::where('status', 'In Progress')->count(),
                'completed' => Audit::where('status', 'Completed')->count(),
            ],
            'findingStats' => [
                'open' => Finding::where('status', 'Open')->count(),
                'in_progress' => Finding::where('status', 'In Progress')->count(),
                'closed' => Finding::where('status', 'Closed')->count(),
            ],
        ]);
    }
}
