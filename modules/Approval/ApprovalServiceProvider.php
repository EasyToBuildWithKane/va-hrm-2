<?php

declare(strict_types=1);

namespace Modules\Approval;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class ApprovalServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Route::middleware(['api', 'auth:sanctum'])
            ->prefix('api/v1/approvals')
            ->group(__DIR__.'/routes/api.php');
    }
}
