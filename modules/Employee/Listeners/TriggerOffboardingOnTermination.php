<?php

declare(strict_types=1);

namespace Modules\Employee\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Employee\Events\EmployeeTerminated;
use Modules\Provisioning\Services\ProvisioningService;

class TriggerOffboardingOnTermination implements ShouldQueue
{
    public string $queue = 'provisioning';

    public function __construct(private readonly ProvisioningService $service)
    {
    }

    public function handle(EmployeeTerminated $event): void
    {
        $this->service->initiateOffboarding($event->employee, $event->reason);
    }
}
