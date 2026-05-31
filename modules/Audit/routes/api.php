<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Audit\Controllers\AuditController;

Route::middleware('permission:audit.view')->group(function (): void {
    Route::get('/logs', [AuditController::class, 'index']);
    Route::get('/logs/{log}', [AuditController::class, 'show']);
    Route::get('/logs/{log}/diff', [AuditController::class, 'diff']);
    Route::get('/employees/{employee}', [AuditController::class, 'forEmployee']);
    Route::get('/workflows/{workflowId}', [AuditController::class, 'forWorkflow']);
    Route::get('/provisioning/{employee}', [AuditController::class, 'forProvisioning']);
    Route::get('/permissions', [AuditController::class, 'permissions']);
});

Route::get('/export', [AuditController::class, 'export'])->middleware('permission:audit.export');
