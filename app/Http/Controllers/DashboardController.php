<?php

namespace App\Http\Controllers;

use App\Models\AuditEngagement;
use App\Models\OracleReportModel;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.index', [
            'engagementCount' => AuditEngagement::count(),
            'openEngagementCount' => AuditEngagement::where('status', 'open')->count(),
            'reportCount' => OracleReportModel::query()->count(),
        ]);
    }
}
