<?php

declare(strict_types=1);

namespace Modules\Provisioning\Actions;

use Modules\Employee\Models\Employee;
use Modules\Provisioning\Models\ProvisioningRequest;
use Modules\Provisioning\Services\ProvisioningService;

final class ProvisionAccountAction
{
    public function __construct(private readonly ProvisioningService $service)
    {
    }

    public function __invoke(Employee $employee): ProvisioningRequest
    {
        return $this->service->initiateOnboarding($employee);
    }
}
