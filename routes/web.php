<?php

use App\Http\Controllers\AuditEngagementController;
use App\Http\Controllers\AuditFieldworkController;
use App\Http\Controllers\AuditFindingController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FollowUpController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
});

Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

Route::redirect('/', '/dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/attendance/check-in', [AttendanceController::class, 'checkIn'])->name('attendance.checkin');
    Route::post('/attendance/check-out', [AttendanceController::class, 'checkOut'])->name('attendance.checkout');

    Route::middleware('role:Admin')->group(function () {
        Route::resource('users', UserController::class)->except('show');
        Route::get('user-activity-logs', [UserController::class, 'logs'])->name('users.logs');
    });

    Route::resource('audits', AuditEngagementController::class)->only(['index', 'create', 'store', 'show']);
    Route::post('audits/{audit}/fieldwork', [AuditFieldworkController::class, 'store'])->name('fieldwork.store');
    Route::post('audits/{audit}/findings', [AuditFindingController::class, 'store'])->name('findings.store');
    Route::post('findings/{finding}/followups', [FollowUpController::class, 'store'])->name('followups.store');
});
