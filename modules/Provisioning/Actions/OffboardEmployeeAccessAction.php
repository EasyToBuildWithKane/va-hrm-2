<?php

declare(strict_types=1);

namespace Modules\Provisioning\Actions;

use Modules\Employee\Models\Employee;
use Modules\Provisioning\Models\ProvisioningRequest;
use Modules\Provisioning\Services\ProvisioningService;

final class OffboardEmployeeAccessAction
{
    public function __construct(private readonly ProvisioningService $service)
    {
    }

    public function __invoke(Employee $employee, ?string $reason = null): ProvisioningRequest
    {
        return $this->service->initiateOffboarding($employee, $reason);
    }
}
