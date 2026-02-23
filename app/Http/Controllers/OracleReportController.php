<?php

namespace App\Http\Controllers;

use App\Services\OracleReportingService;
use Illuminate\Http\Request;

class OracleReportController extends Controller
{
    public function __construct(private readonly OracleReportingService $oracleReportingService)
    {
    }

    public function financial(Request $request)
    {
        $reports = $this->oracleReportingService->financialSummary($request->only([
            'department', 'date_from', 'date_to',
        ]));

        return view('pams.reports.financial', compact('reports'));
    }

    public function transactions(Request $request)
    {
        $reports = $this->oracleReportingService->transactionSummary($request->only([
            'department', 'category',
        ]));

        return view('pams.reports.transactions', compact('reports'));
    }
}
