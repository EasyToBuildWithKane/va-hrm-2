<?php

declare(strict_types=1);

namespace Modules\Attendance;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AttendanceServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Route::middleware(['api', 'auth:sanctum'])
            ->prefix('api/v1')
            ->group(__DIR__.'/routes/api.php');
    }
}
