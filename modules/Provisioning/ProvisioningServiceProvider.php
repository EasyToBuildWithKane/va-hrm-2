<?php

declare(strict_types=1);

namespace Modules\Provisioning;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class ProvisioningServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Route::middleware(['api', 'auth:sanctum'])
            ->prefix('api/v1/provisioning')
            ->group(__DIR__.'/routes/api.php');
    }
}
