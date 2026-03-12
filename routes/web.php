<?php

use App\Http\Controllers\AuditEngagementController;
use App\Http\Controllers\AuditFieldworkController;
use App\Http\Controllers\AuditFindingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FollowUpController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('dashboard'));

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware('role:Admin')->group(function () {
        Route::resource('users', UserController::class)->except('show');
        Route::get('user-activity-logs', [UserController::class, 'logs'])->name('users.logs');
    });

    Route::resource('audits', AuditEngagementController::class)->only(['index', 'create', 'store', 'show']);
    Route::post('audits/{audit}/fieldwork', [AuditFieldworkController::class, 'store'])->name('fieldwork.store');
    Route::post('audits/{audit}/findings', [AuditFindingController::class, 'store'])->name('findings.store');
    Route::post('findings/{finding}/followups', [FollowUpController::class, 'store'])->name('followups.store');
});

Auth::routes();
