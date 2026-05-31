<?php

declare(strict_types=1);

namespace Modules\Leave\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Leave\Models\LeaveQuota;
use Modules\Leave\Models\LeaveRequest;
use Modules\Leave\Repositories\Contracts\LeaveRepositoryInterface;

class LeaveRepository implements LeaveRepositoryInterface
{
    public function paginate(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        return LeaveRequest::query()
            ->with(['employee:id,ulid,first_name,last_name', 'leaveType'])
            ->when($filters['employee_id'] ?? null, fn ($q, $id) => $q->where('employee_id', $id))
            ->when($filters['status'] ?? null, fn ($q, $status) => $q->where('status', $status))
            ->when($filters['leave_type_id'] ?? null, fn ($q, $id) => $q->where('leave_type_id', $id))
            ->when($filters['from'] ?? null, fn ($q, $from) => $q->where('start_date', '>=', $from))
            ->when($filters['to'] ?? null, fn ($q, $to) => $q->where('end_date', '<=', $to))
            ->latest()
            ->paginate($perPage);
    }

    public function findRequest(int $id): ?LeaveRequest
    {
        return LeaveRequest::query()->with(['employee', 'leaveType', 'workflow'])->find($id);
    }

    public function quotasForEmployee(int $employeeId, int $year): iterable
    {
        return LeaveQuota::query()
            ->with('leaveType')
            ->where('employee_id', $employeeId)
            ->where('year', $year)
            ->get();
    }
}
