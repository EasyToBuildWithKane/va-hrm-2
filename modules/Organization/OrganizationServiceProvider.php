<?php

declare(strict_types=1);

namespace Modules\Organization;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class OrganizationServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Route::middleware(['api', 'auth:sanctum'])
            ->prefix('api/v1/organization')
            ->group(__DIR__.'/routes/api.php');
    }
}
