<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Employee\Controllers\EmployeeContractController;
use Modules\Employee\Controllers\EmployeeController;
use Modules\Employee\Controllers\EmployeeDocumentController;
use Modules\Employee\Controllers\EmployeeTimelineController;

Route::get('/', [EmployeeController::class, 'index']);
Route::post('/', [EmployeeController::class, 'store']);
Route::get('/{employee}', [EmployeeController::class, 'show']);
Route::put('/{employee}', [EmployeeController::class, 'update']);
Route::delete('/{employee}', [EmployeeController::class, 'destroy']);
Route::post('/{ulid}/restore', [EmployeeController::class, 'restore']);

Route::get('/{employee}/timeline', [EmployeeTimelineController::class, 'show']);
Route::get('/{employee}/contracts', [EmployeeContractController::class, 'index']);
Route::post('/{employee}/contracts', [EmployeeContractController::class, 'store']);
Route::get('/{employee}/documents', [EmployeeDocumentController::class, 'index']);
Route::post('/{employee}/documents', [EmployeeDocumentController::class, 'store']);

Route::post('/{employee}/onboard', [EmployeeController::class, 'onboard']);
Route::post('/{employee}/offboard', [EmployeeController::class, 'offboard']);
Route::post('/{employee}/terminate', [EmployeeController::class, 'terminate']);
Route::post('/{employee}/transfer', [EmployeeController::class, 'transfer']);
