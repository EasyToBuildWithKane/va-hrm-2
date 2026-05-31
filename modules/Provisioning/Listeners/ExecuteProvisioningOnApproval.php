<?php

declare(strict_types=1);

namespace Modules\Provisioning\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Approval\Events\ApprovalWorkflowCompleted;
use Modules\Provisioning\Engine\ProvisioningEngine;
use Modules\Provisioning\Models\ProvisioningRequest;

class ExecuteProvisioningOnApproval implements ShouldQueue
{
    public string $queue = 'provisioning';

    public function __construct(private readonly ProvisioningEngine $engine)
    {
    }

    public function handle(ApprovalWorkflowCompleted $event): void
    {
        $requestable = $event->workflow->requestable;
        if (! $requestable instanceof ProvisioningRequest) {
            return;
        }

        $this->engine->execute($requestable);
    }
}
