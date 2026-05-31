<?php

declare(strict_types=1);

namespace Modules\Provisioning\Engine;

use Illuminate\Support\Str;
use Modules\Employee\Models\Employee;
use Modules\Provisioning\Models\AccountProvision;

class AccountProvisioner
{
    public function provision(Employee $employee): AccountProvision
    {
        $username = Str::slug("{$employee->first_name}.{$employee->last_name}", '.');

        return AccountProvision::create([
            'employee_id' => $employee->id,
            'account_type' => 'system',
            'account_identifier' => $username,
            'status' => 'active',
            'activated_at' => now(),
        ]);
    }
}
