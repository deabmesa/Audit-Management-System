<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CheckinController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PamsController;
use App\Http\Controllers\StaffInfoController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('login.form'));
Route::get('/up', fn () => response()->json(['status' => 'ok', 'service' => 'audit-management-system']));

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.form');
    Route::post('/login', [AuthController::class, 'login'])->name('login.perform');
});

Route::middleware('auth')->group(function () {
    Route::get('/checkin', [CheckinController::class, 'showForm'])->name('checkin.form');
    Route::post('/checkin', [CheckinController::class, 'checkin'])->name('checkin.perform');
    Route::post('/checkout', [CheckinController::class, 'checkout'])->name('checkout.perform');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout.perform');

    Route::middleware('checkin.required')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('staff-info', StaffInfoController::class)->middleware('role:admin,auditor');

        Route::prefix('pams')->name('pams.')->middleware('role:admin,manager,viewer')->group(function () {
            Route::get('/', [PamsController::class, 'index'])->name('index');
            Route::get('/export/pdf', [PamsController::class, 'exportPdf'])->name('export.pdf');
            Route::get('/export/excel', [PamsController::class, 'exportExcel'])->name('export.excel');
        });
    });
});
