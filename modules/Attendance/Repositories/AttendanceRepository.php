<?php

declare(strict_types=1);

namespace Modules\Attendance\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Attendance\Models\Attendance;
use Modules\Attendance\Repositories\Contracts\AttendanceRepositoryInterface;

class AttendanceRepository implements AttendanceRepositoryInterface
{
    public function paginate(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        return Attendance::query()
            ->with('employee:id,ulid,first_name,last_name')
            ->when($filters['employee_id'] ?? null, fn ($q, $id) => $q->where('employee_id', $id))
            ->when($filters['status'] ?? null, fn ($q, $status) => $q->where('status', $status))
            ->when($filters['from'] ?? null, fn ($q, $from) => $q->where('date', '>=', $from))
            ->when($filters['to'] ?? null, fn ($q, $to) => $q->where('date', '<=', $to))
            ->latest('date')
            ->paginate($perPage);
    }

    public function todayFor(int $employeeId): ?Attendance
    {
        return Attendance::query()
            ->where('employee_id', $employeeId)
            ->where('date', now()->toDateString())
            ->first();
    }
}
