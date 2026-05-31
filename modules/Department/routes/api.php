<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Department\Controllers\DepartmentController;

Route::get('/', [DepartmentController::class, 'index']);
Route::post('/', [DepartmentController::class, 'store']);
Route::get('/{department}', [DepartmentController::class, 'show']);
Route::put('/{department}', [DepartmentController::class, 'update']);
Route::delete('/{department}', [DepartmentController::class, 'destroy']);

Route::get('/{department}/employees', [DepartmentController::class, 'employees']);
Route::get('/{department}/hierarchy', [DepartmentController::class, 'hierarchy']);
Route::get('/{department}/analytics', [DepartmentController::class, 'analytics']);
Route::get('/{department}/headcount', [DepartmentController::class, 'headcount']);
