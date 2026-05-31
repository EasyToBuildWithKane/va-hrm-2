<?php

declare(strict_types=1);

namespace Modules\Attendance\Services;

use Illuminate\Support\Facades\DB;
use Modules\Attendance\Models\AttendanceShift;

class ShiftService
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): AttendanceShift
    {
        return AttendanceShift::create($data);
    }

    /**
     * @param  array<int, int>  $employeeIds
     */
    public function assign(AttendanceShift $shift, array $employeeIds, string $validFrom, ?string $validUntil = null): void
    {
        DB::transaction(function () use ($shift, $employeeIds, $validFrom, $validUntil): void {
            foreach ($employeeIds as $employeeId) {
                DB::table('employee_shifts')->updateOrInsert(
                    ['employee_id' => $employeeId, 'shift_id' => $shift->id, 'valid_from' => $validFrom],
                    ['valid_until' => $validUntil, 'updated_at' => now(), 'created_at' => now()],
                );
            }
        });
    }
}
