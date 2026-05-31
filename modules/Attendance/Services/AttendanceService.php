<?php

declare(strict_types=1);

namespace Modules\Attendance\Services;

use App\Exceptions\WorkflowException;
use Modules\Attendance\Models\Attendance;
use Modules\Attendance\Models\AttendanceCorrection;
use Modules\Attendance\Models\AttendanceShift;
use Modules\Attendance\Repositories\Contracts\AttendanceRepositoryInterface;
use Modules\Employee\Models\Employee;

class AttendanceService
{
    public function __construct(private readonly AttendanceRepositoryInterface $repository)
    {
    }

    public function checkIn(Employee $employee, string $ip): Attendance
    {
        $existing = $this->repository->todayFor($employee->id);

        if ($existing && $existing->check_in_at !== null) {
            throw new WorkflowException('Already checked in today', 'ATTENDANCE_ALREADY_CHECKED_IN', 409);
        }

        $shift = $this->resolveShift($employee);
        $checkInAt = now();
        $lateMinutes = $this->calculateLateMinutes($shift, $checkInAt);

        return Attendance::updateOrCreate(
            ['employee_id' => $employee->id, 'date' => $checkInAt->toDateString()],
            [
                'shift_id' => $shift?->id,
                'check_in_at' => $checkInAt,
                'check_in_ip' => $ip,
                'late_minutes' => $lateMinutes,
                'status' => $lateMinutes > 0 ? 'late' : 'present',
            ],
        );
    }

    public function checkOut(Employee $employee, string $ip): Attendance
    {
        $attendance = $this->repository->todayFor($employee->id);

        if (! $attendance || ! $attendance->check_in_at) {
            throw new WorkflowException('No active check-in record', 'ATTENDANCE_NO_CHECKIN', 422);
        }

        $checkOutAt = now();
        $shift = $attendance->shift;
        $overtime = $this->calculateOvertimeMinutes($shift, $checkOutAt);

        $attendance->update([
            'check_out_at' => $checkOutAt,
            'check_out_ip' => $ip,
            'overtime_minutes' => $overtime,
        ]);

        return $attendance->fresh();
    }

    /**
     * @param  array<string, mixed>  $proposed
     */
    public function submitCorrection(Attendance $attendance, array $proposed, string $reason): AttendanceCorrection
    {
        return AttendanceCorrection::create([
            'attendance_record_id' => $attendance->id,
            'employee_id' => $attendance->employee_id,
            'proposed_values' => $proposed,
            'reason' => $reason,
            'status' => 'pending',
        ]);
    }

    /**
     * @return array<string, int|float>
     */
    public function analytics(?int $employeeId = null): array
    {
        $query = Attendance::query();
        if ($employeeId) {
            $query->where('employee_id', $employeeId);
        }

        return [
            'total_days' => $query->count(),
            'present' => (clone $query)->where('status', 'present')->count(),
            'late' => (clone $query)->where('status', 'late')->count(),
            'absent' => (clone $query)->where('status', 'absent')->count(),
            'avg_overtime_minutes' => (float) (clone $query)->avg('overtime_minutes'),
        ];
    }

    private function resolveShift(Employee $employee): ?AttendanceShift
    {
        return AttendanceShift::query()->where('is_active', true)->first();
    }

    private function calculateLateMinutes(?AttendanceShift $shift, \Illuminate\Support\Carbon $checkInAt): int
    {
        if (! $shift) {
            return 0;
        }

        [$h, $m] = explode(':', $shift->start_time);
        $shiftStart = $checkInAt->copy()->setTime((int) $h, (int) $m);

        $diff = $checkInAt->diffInMinutes($shiftStart, false) * -1;
        $beyondGrace = max(0, $diff - (int) $shift->grace_minutes);

        return $beyondGrace;
    }

    private function calculateOvertimeMinutes(?AttendanceShift $shift, \Illuminate\Support\Carbon $checkOutAt): int
    {
        if (! $shift) {
            return 0;
        }

        [$h, $m] = explode(':', $shift->end_time);
        $shiftEnd = $checkOutAt->copy()->setTime((int) $h, (int) $m);

        return max(0, $checkOutAt->diffInMinutes($shiftEnd, false));
    }
}
