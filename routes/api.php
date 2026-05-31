<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API v1 — Aggregated routes
|--------------------------------------------------------------------------
|
| The RouteServiceProvider already prefixes /api/v1. Module-specific
| routes are loaded by each module's service provider. This file declares
| shared/authentication routes.
|
*/

Route::prefix('auth')->group(function (): void {
    Route::post('/login', [\App\Http\Controllers\Auth\AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->group(function (): void {
        Route::post('/logout', [\App\Http\Controllers\Auth\AuthController::class, 'logout']);
        Route::post('/refresh', [\App\Http\Controllers\Auth\AuthController::class, 'refresh']);
        Route::get('/me', [\App\Http\Controllers\Auth\AuthController::class, 'me']);
    });
});

Route::middleware('auth:sanctum')->get('/health', fn () => ['success' => true, 'message' => 'ok']);
