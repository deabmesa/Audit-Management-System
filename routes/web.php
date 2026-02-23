<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuditController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FindingController;
use App\Http\Controllers\OracleReportController;
use App\Http\Controllers\AuditPlanController;
use App\Http\Controllers\WorkpaperController;
use App\Http\Controllers\RecommendationController;
use App\Http\Controllers\StaffController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/attendance/checkout', [AttendanceController::class, 'checkOut'])->name('attendance.checkout');

    Route::middleware('role:Admin,Manager')->group(function () {
        Route::get('/staff', [StaffController::class, 'index'])->name('staff.index');
        Route::post('/staff', [StaffController::class, 'store'])->name('staff.store');

        Route::get('/audits', [AuditController::class, 'index'])->name('audits.index');
        Route::post('/audits', [AuditController::class, 'store'])->name('audits.store');
        Route::post('/audits/{audit}/assign', [AuditController::class, 'assign'])->name('audits.assign');
    });

    Route::middleware('role:Admin,Auditor,Manager,Viewer')->group(function () {

        Route::get('/pams/plans', [AuditPlanController::class, 'index'])->name('plans.index');
        Route::post('/pams/plans', [AuditPlanController::class, 'store'])->name('plans.store');

        Route::get('/pams/workpapers', [WorkpaperController::class, 'index'])->name('workpapers.index');
        Route::post('/pams/workpapers', [WorkpaperController::class, 'store'])->name('workpapers.store');
        Route::get('/pams/findings', [FindingController::class, 'index'])->name('findings.index');
        Route::post('/pams/findings', [FindingController::class, 'store'])->name('findings.store');

        Route::get('/pams/recommendations', [RecommendationController::class, 'index'])->name('recommendations.index');
        Route::post('/pams/recommendations', [RecommendationController::class, 'store'])->name('recommendations.store');

        Route::get('/pams/reports/financial', [OracleReportController::class, 'financial'])->name('reports.financial');
        Route::get('/pams/reports/transactions', [OracleReportController::class, 'transactions'])->name('reports.transactions');
    });
});


require __DIR__.'/auth.php';
