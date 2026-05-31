<?php

declare(strict_types=1);

namespace Modules\Request;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class RequestServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Route::middleware(['api', 'auth:sanctum'])
            ->prefix('api/v1/requests')
            ->group(__DIR__.'/routes/api.php');
    }
}
