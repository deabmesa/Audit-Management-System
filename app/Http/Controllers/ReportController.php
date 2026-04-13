<?php

namespace App\Http\Controllers;

use App\Models\Finding;
use App\Services\AuditHashService;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function findingPdf(Finding $finding, AuditHashService $hashService)
    {
        $finding->load(['evidence.versions', 'approvals.step']);
        $auditHash = $hashService->findingHash($finding);
        $pdf = Pdf::loadView('reports.finding', compact('finding', 'auditHash'));
        return $pdf->download('finding-'.$finding->id.'.pdf');
    }
}
