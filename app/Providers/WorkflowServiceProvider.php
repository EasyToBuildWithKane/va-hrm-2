<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Approval\Engine\ApprovalChainResolver;
use Modules\Approval\Engine\ApprovalEngine;
use Modules\Approval\Engine\DelegationResolver;
use Modules\Approval\Engine\EscalationHandler;
use Modules\Approval\Engine\SlaTracker;

class WorkflowServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ApprovalEngine::class);
        $this->app->singleton(ApprovalChainResolver::class);
        $this->app->singleton(EscalationHandler::class);
        $this->app->singleton(DelegationResolver::class);
        $this->app->singleton(SlaTracker::class);
    }
}
