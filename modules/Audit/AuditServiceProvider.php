<?php

declare(strict_types=1);

namespace Modules\Audit;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AuditServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Route::middleware(['api', 'auth:sanctum'])
            ->prefix('api/v1/audit')
            ->group(__DIR__.'/routes/api.php');
    }
}
