<?php

declare(strict_types=1);

namespace Modules\Department;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class DepartmentServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Route::middleware(['api', 'auth:sanctum'])
            ->prefix('api/v1/departments')
            ->group(__DIR__.'/routes/api.php');
    }
}
