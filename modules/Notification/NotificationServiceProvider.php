<?php

declare(strict_types=1);

namespace Modules\Notification;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Modules\Notification\Services\NotificationService;

class NotificationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(NotificationService::class);
    }

    public function boot(): void
    {
        Route::middleware(['api', 'auth:sanctum'])
            ->prefix('api/v1/notifications')
            ->group(__DIR__.'/routes/api.php');
    }
}
