<?php

namespace App\Http\Controllers;

use App\Models\Audit;
use App\Models\Issue;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $totalAudits = Audit::count();
        $openIssues = Issue::where('status', 'Open')->count();
        $overdueIssues = Issue::where('status', '!=', 'Closed')->whereDate('due_date', '<', now())->count();
        $statusByAudit = Audit::selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status');

        return view('dashboard.index', compact('totalAudits', 'openIssues', 'overdueIssues', 'statusByAudit'));
    }
}
