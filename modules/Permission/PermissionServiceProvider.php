<?php

declare(strict_types=1);

namespace Modules\Permission;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class PermissionServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadRoutes();
    }

    private function loadRoutes(): void
    {
        Route::middleware(['api', 'auth:sanctum'])
            ->prefix('api/v1/permissions')
            ->group(__DIR__.'/routes/api.php');
    }
}
