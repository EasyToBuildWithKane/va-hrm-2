<?php

declare(strict_types=1);

namespace Modules\Provisioning\Engine;

use Illuminate\Support\Facades\DB;
use Modules\Employee\Models\Employee;
use Modules\Provisioning\Events\AccessRevoked;
use Modules\Provisioning\Models\AccountProvision;
use Modules\Provisioning\Models\SoftwareLicense;

class AccessRevoker
{
    public function revokeAll(Employee $employee, string $reason = 'employee_offboarding'): void
    {
        DB::transaction(function () use ($employee): void {
            AccountProvision::query()
                ->where('employee_id', $employee->id)
                ->whereIn('status', ['active', 'suspended'])
                ->update([
                    'status' => 'revoked',
                    'revoked_at' => now(),
                ]);

            $licenses = DB::table('employee_software_licenses')
                ->where('employee_id', $employee->id)
                ->whereNull('revoked_at')
                ->get();

            foreach ($licenses as $row) {
                DB::table('employee_software_licenses')
                    ->where('id', $row->id)
                    ->update(['revoked_at' => now()]);
                SoftwareLicense::query()->where('id', $row->software_license_id)->decrement('used_seats');
            }

            DB::table('model_has_roles')
                ->where('model_type', $employee->user::class ?? \App\Models\User::class)
                ->where('model_id', $employee->user_id)
                ->delete();
        });

        event(new AccessRevoked($employee, $reason));
    }

    public function suspend(AccountProvision $account): AccountProvision
    {
        $account->update(['status' => 'suspended', 'suspended_at' => now()]);

        return $account->fresh();
    }

    public function activate(AccountProvision $account): AccountProvision
    {
        $account->update(['status' => 'active', 'activated_at' => now()]);

        return $account->fresh();
    }

    public function revoke(AccountProvision $account): AccountProvision
    {
        $account->update(['status' => 'revoked', 'revoked_at' => now()]);

        return $account->fresh();
    }
}
