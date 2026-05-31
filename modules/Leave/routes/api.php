<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Leave\Controllers\LeaveController;

Route::get('/types', [LeaveController::class, 'types']);
Route::get('/quotas', [LeaveController::class, 'myQuotas']);
Route::get('/quotas/{employee}', [LeaveController::class, 'quotasFor']);

Route::get('/requests', [LeaveController::class, 'index']);
Route::post('/requests', [LeaveController::class, 'store']);
Route::get('/requests/{leaveRequest}', [LeaveController::class, 'show']);
Route::delete('/requests/{leaveRequest}', [LeaveController::class, 'destroy']);

Route::get('/approvals', [LeaveController::class, 'approvals']);
Route::get('/analytics', [LeaveController::class, 'analytics']);

Route::get('/policies', [LeaveController::class, 'policies']);
Route::post('/policies', [LeaveController::class, 'storePolicy'])->middleware('permission:leave.policy.manage');
Route::put('/policies/{policy}', [LeaveController::class, 'updatePolicy'])->middleware('permission:leave.policy.manage');
