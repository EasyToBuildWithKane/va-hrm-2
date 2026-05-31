<?php

declare(strict_types=1);

namespace Modules\Provisioning\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Employee\Models\Employee;
use Modules\Provisioning\Engine\ProvisioningEngine;
use Modules\Provisioning\Models\ProvisioningRequest;
use Modules\Provisioning\Models\SoftwareLicense;

class ProvisioningService
{
    public function __construct(private readonly ProvisioningEngine $engine)
    {
    }

    public function initiateOnboarding(Employee $employee): ProvisioningRequest
    {
        $request = ProvisioningRequest::create([
            'ulid' => (string) Str::ulid(),
            'employee_id' => $employee->id,
            'type' => 'onboarding',
            'status' => 'pending',
            'requested_by' => Auth::id() ?? $employee->user_id,
        ]);

        $this->engine->execute($request);

        return $request->fresh();
    }

    public function initiateOffboarding(Employee $employee, ?string $reason = null): ProvisioningRequest
    {
        $request = ProvisioningRequest::create([
            'ulid' => (string) Str::ulid(),
            'employee_id' => $employee->id,
            'type' => 'offboarding',
            'status' => 'pending',
            'requested_by' => Auth::id() ?? $employee->user_id,
            'metadata' => ['reason' => $reason],
        ]);

        $this->engine->execute($request);

        return $request->fresh();
    }

    public function assignLicense(Employee $employee, SoftwareLicense $license): void
    {
        if ($license->used_seats >= $license->total_seats) {
            return;
        }

        $employee->user; // touch
        DB::table('employee_software_licenses')->insert([
            'employee_id' => $employee->id,
            'software_license_id' => $license->id,
            'assigned_at' => now(),
            'assigned_by' => Auth::id() ?? 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $license->increment('used_seats');
    }

    public function revokeLicense(Employee $employee, SoftwareLicense $license): void
    {
        $rows = DB::table('employee_software_licenses')
            ->where('employee_id', $employee->id)
            ->where('software_license_id', $license->id)
            ->whereNull('revoked_at')
            ->update(['revoked_at' => now(), 'updated_at' => now()]);

        if ($rows > 0) {
            $license->decrement('used_seats');
        }
    }
}
