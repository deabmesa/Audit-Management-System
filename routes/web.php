<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EvidenceController;
use App\Http\Controllers\FindingController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\WorkflowController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('dashboard'));

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/chart-data', [DashboardController::class, 'chartData'])->name('dashboard.chart-data');

    Route::resource('findings', FindingController::class);
    Route::post('findings/{finding}/approve', [WorkflowController::class, 'approve'])->name('findings.approve');
    Route::post('findings/{finding}/reject', [WorkflowController::class, 'reject'])->name('findings.reject');
    Route::get('findings/{finding}/timeline', [WorkflowController::class, 'timeline'])->name('findings.timeline');

    Route::get('findings/{finding}/evidence', [EvidenceController::class, 'index'])->name('evidence.index');
    Route::post('findings/{finding}/evidence', [EvidenceController::class, 'store'])->name('evidence.store');
    Route::get('evidence/{evidence}/download/{version}', [EvidenceController::class, 'download'])->name('evidence.download');
    Route::get('evidence/{evidence}/compare/{left}/{right}', [EvidenceController::class, 'compare'])->name('evidence.compare');

    Route::middleware('role:Admin')->group(function () {
        Route::resource('roles', RoleController::class);
        Route::get('menus', [MenuController::class, 'index'])->name('menus.index');
        Route::post('menus', [MenuController::class, 'store'])->name('menus.store');
        Route::get('workflow/designer', [WorkflowController::class, 'designer'])->name('workflow.designer');
        Route::post('workflow/designer', [WorkflowController::class, 'saveDesigner'])->name('workflow.save-designer');
    });

    Route::get('reports/{finding}/pdf', [ReportController::class, 'findingPdf'])->name('reports.finding.pdf');
    Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
});

require __DIR__.'/auth.php';
