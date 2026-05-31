<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Contribution\Controllers\ContributionController;

Route::get('/dashboard', [ContributionController::class, 'dashboard']);
Route::get('/ranking', [ContributionController::class, 'ranking']);
Route::get('/employees/{employee}', [ContributionController::class, 'employeeScore']);
Route::get('/employees/{employee}/history', [ContributionController::class, 'employeeHistory']);

Route::post('/adjustments', [ContributionController::class, 'adjustments'])->middleware('permission:contribution.score.adjust');

Route::middleware('permission:contribution.rules.manage')->group(function (): void {
    Route::get('/rules', [ContributionController::class, 'rules']);
    Route::post('/rules', [ContributionController::class, 'storeRule']);
    Route::put('/rules/{rule}', [ContributionController::class, 'updateRule']);
});
