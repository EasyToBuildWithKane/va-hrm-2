<?php

declare(strict_types=1);

namespace Modules\Leave;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class LeaveServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Route::middleware(['api', 'auth:sanctum'])
            ->prefix('api/v1/leave')
            ->group(__DIR__.'/routes/api.php');
    }
}
