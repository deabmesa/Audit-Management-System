<?php

use App\Http\Controllers\AuditManagementController;
use App\Http\Controllers\CheckInController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportTemplateController;
use App\Http\Controllers\RuleManagementController;
use App\Http\Controllers\StaffInformationController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/staff-information', [StaffInformationController::class, 'index'])->name('staff.index');

    Route::get('/pams', [AuditManagementController::class, 'index'])->name('audit.index');
    Route::post('/pams/programs', [AuditManagementController::class, 'storeProgram'])->name('audit.programs.store');

    Route::get('/reports/templates', [ReportTemplateController::class, 'index'])->name('reports.templates.index');
    Route::post('/reports/templates', [ReportTemplateController::class, 'store'])->name('reports.templates.store');
    Route::get('/reports/templates/{reportTemplate}/run', [ReportTemplateController::class, 'run'])->name('reports.templates.run');

    Route::get('/reports/rules', [RuleManagementController::class, 'index'])->name('reports.rules.index');
    Route::post('/reports/rules', [RuleManagementController::class, 'store'])->name('reports.rules.store');

    Route::post('/checkin', [CheckInController::class, 'store'])->name('checkin.store');
    Route::post('/checkin/{checkInRecord}/checkout', [CheckInController::class, 'checkout'])->name('checkin.checkout');
});
