<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Attendance\Controllers\AttendanceController;
use Modules\Attendance\Controllers\ShiftController;

Route::prefix('attendance')->group(function (): void {
    Route::post('/check-in', [AttendanceController::class, 'checkIn']);
    Route::post('/check-out', [AttendanceController::class, 'checkOut']);
    Route::get('/', [AttendanceController::class, 'index']);
    Route::get('/analytics', [AttendanceController::class, 'analytics']);
    Route::get('/{attendance}', [AttendanceController::class, 'show']);
    Route::post('/corrections', [AttendanceController::class, 'correction']);
});

Route::prefix('shifts')->group(function (): void {
    Route::get('/', [ShiftController::class, 'index']);
    Route::post('/', [ShiftController::class, 'store']);
    Route::put('/{shift}', [ShiftController::class, 'update']);
    Route::delete('/{shift}', [ShiftController::class, 'destroy']);
    Route::post('/{shift}/assign', [ShiftController::class, 'assign']);
});
