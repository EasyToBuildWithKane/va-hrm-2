<?php

declare(strict_types=1);

namespace Modules\Provisioning\Engine;

use Illuminate\Support\Str;
use Modules\Employee\Models\Employee;
use Modules\Provisioning\Models\AccountProvision;
use Modules\Provisioning\Models\EmailProvision;

class EmailProvisioner
{
    public function provision(Employee $employee): AccountProvision
    {
        $email = $this->buildEmail($employee);

        $account = AccountProvision::create([
            'employee_id' => $employee->id,
            'account_type' => 'email',
            'account_identifier' => $email,
            'status' => 'active',
            'activated_at' => now(),
        ]);

        EmailProvision::create([
            'employee_id' => $employee->id,
            'account_provision_id' => $account->id,
            'email_address' => $email,
            'mailbox_type' => 'standard',
        ]);

        return $account;
    }

    private function buildEmail(Employee $employee): string
    {
        $domain = config('provisioning.email_domain', 'company.com');
        $local = Str::slug("{$employee->first_name}.{$employee->last_name}", '.');

        return "{$local}@{$domain}";
    }
}
