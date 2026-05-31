<?php

declare(strict_types=1);

namespace Modules\Provisioning\Engine;

use Modules\Employee\Models\Employee;
use Modules\Provisioning\Models\AccountProvision;

class AssetProvisioner
{
    public function provision(Employee $employee, string $assetIdentifier, string $type = 'device'): AccountProvision
    {
        return AccountProvision::create([
            'employee_id' => $employee->id,
            'account_type' => $type,
            'account_identifier' => $assetIdentifier,
            'status' => 'active',
            'activated_at' => now(),
        ]);
    }
}
