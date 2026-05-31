<?php

declare(strict_types=1);

namespace Modules\Employee\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Employee\Events\EmployeeCreated;
use Modules\Provisioning\Services\ProvisioningService;

class TriggerProvisioningOnCreate implements ShouldQueue
{
    public string $queue = 'provisioning';

    public function __construct(private readonly ProvisioningService $service)
    {
    }

    public function handle(EmployeeCreated $event): void
    {
        $this->service->initiateOnboarding($event->employee);
    }
}
