<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Provisioning\Controllers\ProvisioningController;
use Modules\Provisioning\Controllers\SoftwareLicenseController;

Route::get('/dashboard', [ProvisioningController::class, 'dashboard']);

Route::get('/accounts', [ProvisioningController::class, 'accounts']);
Route::post('/accounts', [ProvisioningController::class, 'storeAccount'])->middleware('permission:provisioning.account.manage');
Route::get('/accounts/{account}', [ProvisioningController::class, 'showAccount']);
Route::patch('/accounts/{account}/suspend', [ProvisioningController::class, 'suspend'])->middleware('permission:provisioning.account.manage');
Route::patch('/accounts/{account}/activate', [ProvisioningController::class, 'activate'])->middleware('permission:provisioning.account.manage');
Route::patch('/accounts/{account}/revoke', [ProvisioningController::class, 'revoke'])->middleware('permission:provisioning.account.manage');

Route::middleware('permission:provisioning.license.manage')->group(function (): void {
    Route::get('/licenses', [SoftwareLicenseController::class, 'index']);
    Route::post('/licenses', [SoftwareLicenseController::class, 'store']);
    Route::put('/licenses/{license}', [SoftwareLicenseController::class, 'update']);
    Route::post('/licenses/{license}/assign', [SoftwareLicenseController::class, 'assign']);
    Route::delete('/licenses/{license}/revoke/{employee}', [SoftwareLicenseController::class, 'revoke']);
});

Route::post('/onboarding/{employee}', [ProvisioningController::class, 'triggerOnboarding'])->middleware('permission:provisioning.account.manage');
Route::post('/offboarding/{employee}', [ProvisioningController::class, 'triggerOffboarding'])->middleware('permission:provisioning.offboarding.execute');
Route::get('/logs/{employee}', [ProvisioningController::class, 'logs']);
