<?php

namespace App\Http\Controllers;

use App\Models\Audit;
use App\Models\AuditFinding;

class DashboardController extends Controller
{
    public function index()
    {
        $summary = [
            'total_audits' => Audit::count(),
            'open_findings' => AuditFinding::where('status', 'open')->count(),
            'overdue_findings' => AuditFinding::where('status', '!=', 'closed')->whereDate('due_date', '<', now())->count(),
            'completed_audits' => Audit::where('status', 'completed')->count(),
        ];

        $chartData = [
            'labels' => ['Open', 'In Progress', 'Closed'],
            'values' => [
                AuditFinding::where('status', 'open')->count(),
                AuditFinding::where('status', 'in_progress')->count(),
                AuditFinding::where('status', 'closed')->count(),
            ],
        ];

        return view('dashboard.index', compact('summary', 'chartData'));
    }
}
