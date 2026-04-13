<?php

namespace App\Http\Controllers;

use App\Models\Finding;

class DashboardController extends Controller
{
    public function index()
    {
        $kpi = $this->kpiData();
        return view('dashboard.index', compact('kpi'));
    }

    public function kpi()
    {
        return response()->json($this->kpiData());
    }

    public function chartData()
    {
        $status = Finding::selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status');
        return response()->json($status);
    }

    private function kpiData(): array
    {
        return [
            'total_findings' => Finding::count(),
            'open_findings' => Finding::where('status', 'open')->count(),
            'approved_findings' => Finding::where('status', 'approved')->count(),
            'closed_findings' => Finding::where('status', 'closed')->count(),
        ];
    }
}
