<?php

declare(strict_types=1);

namespace Modules\Employee\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Organization\Services\OrganizationGraphService;

class UpdateOrganizationGraphOnChange implements ShouldQueue
{
    public string $queue = 'default';

    public function __construct(private readonly OrganizationGraphService $service)
    {
    }

    public function handle(object $event): void
    {
        $employee = property_exists($event, 'employee') ? $event->employee : null;

        if ($employee) {
            $this->service->syncEmployee($employee);
        }
    }
}
