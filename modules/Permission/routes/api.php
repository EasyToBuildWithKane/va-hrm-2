<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Permission\Controllers\PermissionController;
use Modules\Permission\Controllers\PermissionDelegationController;
use Modules\Permission\Controllers\RoleController;

Route::get('/roles', [RoleController::class, 'index']);
Route::post('/roles', [RoleController::class, 'store'])->middleware('permission:permission.role.manage');
Route::put('/roles/{role}', [RoleController::class, 'update'])->middleware('permission:permission.role.manage');
Route::post('/roles/{role}/sync', [RoleController::class, 'sync'])->middleware('permission:permission.role.manage');
Route::get('/matrix', [RoleController::class, 'matrix'])->middleware('permission:permission.matrix.view');

Route::get('/users/{user}', [PermissionController::class, 'userPermissions']);
Route::post('/users/{user}/assign', [PermissionController::class, 'assignRole'])->middleware('permission:permission.role.manage');
Route::delete('/users/{user}/revoke/{role}', [PermissionController::class, 'revokeRole'])->middleware('permission:permission.role.manage');

Route::get('/delegate', [PermissionDelegationController::class, 'index']);
Route::post('/delegate', [PermissionDelegationController::class, 'store'])->middleware('permission:permission.delegate');
Route::delete('/delegate/{id}', [PermissionDelegationController::class, 'destroy'])->middleware('permission:permission.delegate');
