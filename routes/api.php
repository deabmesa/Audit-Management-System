<?php

use App\Http\Controllers\Api\AuditApiController;
use App\Http\Controllers\Api\IssueApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/token', function (Request $request) {
    $request->validate(['email' => 'required|email', 'password' => 'required']);

    $user = \App\Models\User::where('email', $request->email)->firstOrFail();

    if (!\Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
        abort(401, 'Invalid credentials.');
    }

    return ['token' => $user->createToken('api-token')->plainTextToken];
});

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('audits', AuditApiController::class);
    Route::apiResource('issues', IssueApiController::class);
});
