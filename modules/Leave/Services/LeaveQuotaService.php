<?php

declare(strict_types=1);

namespace Modules\Leave\Services;

use App\Exceptions\WorkflowException;
use Modules\Employee\Models\Employee;
use Modules\Leave\Models\LeaveQuota;
use Modules\Leave\Models\LeaveType;

class LeaveQuotaService
{
    public function deductDays(Employee $employee, LeaveType $leaveType, float $days): LeaveQuota
    {
        $year = (int) now()->year;
        $quota = LeaveQuota::firstOrCreate(
            ['employee_id' => $employee->id, 'leave_type_id' => $leaveType->id, 'year' => $year],
            ['entitled_days' => $leaveType->days_per_year],
        );

        if ($quota->remainingDays() < $days) {
            throw new WorkflowException(
                "Insufficient leave quota: {$quota->remainingDays()} remaining, {$days} requested",
                'LEAVE_QUOTA_EXCEEDED',
                422,
            );
        }

        $quota->increment('used_days', $days);

        return $quota->fresh();
    }

    public function refundDays(Employee $employee, LeaveType $leaveType, float $days): LeaveQuota
    {
        $year = (int) now()->year;
        $quota = LeaveQuota::firstOrCreate(
            ['employee_id' => $employee->id, 'leave_type_id' => $leaveType->id, 'year' => $year],
            ['entitled_days' => $leaveType->days_per_year],
        );

        $quota->decrement('used_days', $days);

        return $quota->fresh();
    }

    public function adjust(Employee $employee, LeaveType $leaveType, int $year, float $delta, string $reason): LeaveQuota
    {
        $quota = LeaveQuota::firstOrCreate(
            ['employee_id' => $employee->id, 'leave_type_id' => $leaveType->id, 'year' => $year],
            ['entitled_days' => $leaveType->days_per_year],
        );

        $quota->increment('entitled_days', $delta);

        return $quota->fresh();
    }
}
