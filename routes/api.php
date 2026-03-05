<?php

use App\Http\Controllers\Api\AuditApiController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/audits', [AuditApiController::class, 'index']);
    Route::post('/audits', [AuditApiController::class, 'store']);
});
