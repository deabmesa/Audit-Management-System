<?php

use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\AuditController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IssueController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('dashboard'));

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::resource('audits', AuditController::class);
    Route::get('audits/{audit}/issues/create', [IssueController::class, 'create'])->name('audits.issues.create');
    Route::post('audits/{audit}/issues', [IssueController::class, 'store'])->name('audits.issues.store');

    Route::resource('issues', IssueController::class)->except(['index', 'create', 'store']);

    Route::post('audits/{audit}/attachments', [AttachmentController::class, 'storeForAudit'])->name('audits.attachments.store');
    Route::post('issues/{issue}/attachments', [AttachmentController::class, 'storeForIssue'])->name('issues.attachments.store');
    Route::get('attachments/{attachment}/download', [AttachmentController::class, 'download'])->name('attachments.download');
});

require __DIR__.'/auth.php';
