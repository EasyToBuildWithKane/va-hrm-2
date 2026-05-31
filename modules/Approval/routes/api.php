<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Approval\Controllers\ApprovalController;
use Modules\Approval\Controllers\WorkflowConfigurationController;

Route::get('/queue', [ApprovalController::class, 'queue']);
Route::get('/history', [ApprovalController::class, 'history']);
Route::get('/analytics', [ApprovalController::class, 'analytics']);

Route::get('/workflows/{workflow}', [ApprovalController::class, 'show']);
Route::post('/workflows/{workflow}/approve', [ApprovalController::class, 'approve']);
Route::post('/workflows/{workflow}/reject', [ApprovalController::class, 'reject']);
Route::post('/workflows/{workflow}/delegate', [ApprovalController::class, 'delegate']);
Route::post('/workflows/{workflow}/escalate', [ApprovalController::class, 'escalate']);

Route::middleware('permission:approval.workflow.configure')->group(function (): void {
    Route::get('/configurations', [WorkflowConfigurationController::class, 'index']);
    Route::post('/configurations', [WorkflowConfigurationController::class, 'store']);
    Route::put('/configurations/{configuration}', [WorkflowConfigurationController::class, 'update']);
});
