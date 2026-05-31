<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Organization\Controllers\OrganizationGraphController;

Route::get('/graph', [OrganizationGraphController::class, 'graph']);
Route::get('/graph/{nodeId}/subtree', [OrganizationGraphController::class, 'subtree']);
Route::get('/employees/{employee}/reporting-chain', [OrganizationGraphController::class, 'reportingChain']);
Route::get('/departments/{department}/hierarchy', [OrganizationGraphController::class, 'departmentHierarchy']);

Route::post('/relationships', [OrganizationGraphController::class, 'storeRelationship']);
Route::put('/relationships/{relationship}', [OrganizationGraphController::class, 'updateRelationship']);
Route::delete('/relationships/{relationship}', [OrganizationGraphController::class, 'destroyRelationship']);

Route::post('/sync', [OrganizationGraphController::class, 'sync'])->middleware('permission:permission.role.manage');
