<?php

namespace App\Http\Controllers;

use App\Models\AuditEngagement;
use App\Models\AuditFinding;
use App\Models\FollowUp;
use App\Models\UserActivityLog;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $totalAudits = AuditEngagement::count();
        $openFindings = AuditFinding::whereHas('followUps', fn ($q) => $q->where('status', 'Open'))->count();
        $riskDistribution = AuditFinding::selectRaw('risk_rating, COUNT(*) as total')
            ->groupBy('risk_rating')
            ->pluck('total', 'risk_rating');
        $followUpProgress = [
            'open' => FollowUp::where('status', 'Open')->count(),
            'closed' => FollowUp::where('status', 'Closed')->count(),
        ];

        $lastAttendance = UserActivityLog::where('user_id', Auth::id())
            ->whereIn('activity', ['Checked in', 'Checked out'])
            ->latest('id')
            ->first();

        $isCheckedIn = $lastAttendance?->activity === 'Checked in';
        $lastAttendanceAt = $lastAttendance?->created_at?->format('Y-m-d H:i');

        return view('dashboard.index', compact(
            'totalAudits',
            'openFindings',
            'riskDistribution',
            'followUpProgress',
            'isCheckedIn',
            'lastAttendanceAt'
        ));
    }
}
