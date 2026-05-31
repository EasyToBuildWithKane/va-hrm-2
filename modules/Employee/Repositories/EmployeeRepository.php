<?php

declare(strict_types=1);

namespace Modules\Employee\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Employee\Models\Employee;
use Modules\Employee\Repositories\Contracts\EmployeeRepositoryInterface;

class EmployeeRepository implements EmployeeRepositoryInterface
{
    public function paginate(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        return Employee::query()
            ->with(['department:id,ulid,name', 'position:id,ulid,name', 'manager:id,ulid,first_name,last_name'])
            ->when($filters['search'] ?? null, fn ($q, $term) => $q->where(function ($qq) use ($term): void {
                $qq->where('first_name', 'like', "%{$term}%")
                    ->orWhere('last_name', 'like', "%{$term}%")
                    ->orWhere('email', 'like', "%{$term}%")
                    ->orWhere('employee_number', 'like', "%{$term}%");
            }))
            ->when($filters['status'] ?? null, fn ($q, $status) => $q->where('employment_status', $status))
            ->when($filters['department_id'] ?? null, fn ($q, $deptId) => $q->where('department_id', $deptId))
            ->when($filters['employment_type'] ?? null, fn ($q, $type) => $q->where('employment_type', $type))
            ->when($filters['from'] ?? null, fn ($q, $from) => $q->where('join_date', '>=', $from))
            ->when($filters['to'] ?? null, fn ($q, $to) => $q->where('join_date', '<=', $to))
            ->orderBy($filters['sort'] ?? 'created_at', $filters['direction'] ?? 'desc')
            ->paginate($perPage);
    }

    public function find(int $id): ?Employee
    {
        return Employee::query()->with(['department', 'position', 'manager'])->find($id);
    }

    public function findByUlid(string $ulid): ?Employee
    {
        return Employee::query()->where('ulid', $ulid)->first();
    }

    public function create(array $data): Employee
    {
        return Employee::create($data);
    }

    public function update(Employee $employee, array $data): Employee
    {
        $employee->update($data);

        return $employee->fresh();
    }

    public function delete(Employee $employee): bool
    {
        return (bool) $employee->delete();
    }

    public function emailExists(string $email, ?int $excludeId = null): bool
    {
        return Employee::query()
            ->where('email', $email)
            ->when($excludeId, fn ($q, $id) => $q->where('id', '!=', $id))
            ->exists();
    }
}
