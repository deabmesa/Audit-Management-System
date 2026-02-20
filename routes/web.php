<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SimplePageController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
});

Route::middleware('auth')->group(function (): void {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    Route::get('/change-password', [PasswordController::class, 'edit'])->name('password.edit');
    Route::put('/change-password', [PasswordController::class, 'update'])->name('password.update');

    Route::get('/', DashboardController::class)->name('dashboard');

    Route::prefix('develop-task')->name('tasks.')->group(function (): void {
        Route::get('/list', [TaskController::class, 'index'])->name('list');
        Route::get('/assignment', [TaskController::class, 'assignment'])->name('assignment');
        Route::get('/status', [TaskController::class, 'status'])->name('status');
    });

    Route::middleware('role:Admin')->prefix('user')->name('user.')->group(function (): void {
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::get('/manage', [UserController::class, 'index'])->name('manage');
    });

    Route::middleware('role:Admin')->prefix('administrator')->name('administrator.')->group(function (): void {
        Route::view('/roles', 'admin.roles')->name('roles');
        Route::view('/permissions', 'admin.permissions')->name('permissions');
    });

    Route::view('/settings/general', 'settings.general')->name('settings.general');
    Route::view('/settings/logs', 'settings.logs')->name('settings.logs');

    Route::prefix('pre-audit')->name('preaudit.')->group(function (): void {
        Route::view('/checklist', 'preaudit.checklist')->name('checklist');
        Route::view('/risk-assessment', 'preaudit.risk')->name('risk');
    });

    Route::prefix('fieldwork')->name('fieldwork.')->group(function (): void {
        Route::view('/tasks', 'fieldwork.tasks')->name('tasks');
        Route::view('/evidence-upload', 'fieldwork.evidence')->name('evidence');
    });

    Route::prefix('audit-report')->name('reports.')->group(function (): void {
        Route::view('/draft', 'reports.draft')->name('draft');
        Route::view('/final', 'reports.final')->name('final');
    });

    Route::prefix('management-report')->name('management.')->group(function (): void {
        Route::view('/summary', 'management.summary')->name('summary');
        Route::view('/kpi', 'management.kpi')->name('kpi');
    });

    Route::prefix('other')->name('other.')->group(function (): void {
        Route::view('/notes', 'other.notes')->name('notes');
        Route::view('/reminders', 'other.reminders')->name('reminders');
    });

    Route::view('/contact-list', 'contact.index')->name('contact.index');
    Route::get('/exports/issues/{format}', [SimplePageController::class, 'export'])->name('issues.export');
});
