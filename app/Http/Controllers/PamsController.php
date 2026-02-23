<?php

namespace App\Http\Controllers;

use App\Models\OracleReportModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PamsController extends Controller
{
    public function index(Request $request)
    {
        $reports = OracleReportModel::query()
            ->when($request->filled('keyword'), fn ($q) => $q->where('AUDIT_NAME', 'like', '%'.$request->string('keyword').'%'))
            ->when($request->filled('status'), fn ($q) => $q->where('STATUS', $request->string('status')))
            ->orderByDesc('REPORT_DATE')
            ->paginate(20)
            ->withQueryString();

        return view('pams.index', compact('reports'));
    }

    public function exportPdf(Request $request)
    {
        $rows = OracleReportModel::query()->limit(1000)->get();
        $pdf = Pdf::loadView('pams.pdf', compact('rows'));

        return $pdf->download('pams-report.pdf');
    }

    public function exportExcel(): BinaryFileResponse
    {
        return Excel::download(new \App\Exports\PamsReportExport(), 'pams-report.xlsx');
    }
}
