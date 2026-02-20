<?php

namespace App\Http\Controllers;

use App\Models\Audit;
use App\Models\Issue;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('dashboard.index', [
            'auditCount' => Audit::count(),
            'openIssues' => Issue::where('status', 'Open')->count(),
            'closedIssues' => Issue::where('status', 'Closed')->count(),
            'dueSoon' => Issue::whereBetween('due_date', [now(), now()->addWeek()])->count(),
        ]);
    }
}
