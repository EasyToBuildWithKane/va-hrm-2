<?php

declare(strict_types=1);

namespace Modules\Department\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Modules\Department\Models\Department;
use Modules\Department\Repositories\Contracts\DepartmentRepositoryInterface;

class DepartmentRepository implements DepartmentRepositoryInterface
{
    public function paginate(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        return Department::query()
            ->when($filters['search'] ?? null, fn ($q, $term) => $q->where(function ($qq) use ($term): void {
                $qq->where('name', 'like', "%{$term}%")->orWhere('code', 'like', "%{$term}%");
            }))
            ->when(($filters['is_active'] ?? null) !== null, fn ($q) => $q->where('is_active', (bool) $filters['is_active']))
            ->when($filters['parent_id'] ?? null, fn ($q, $pid) => $q->where('parent_id', $pid))
            ->orderBy($filters['sort'] ?? 'name', $filters['direction'] ?? 'asc')
            ->paginate($perPage);
    }

    public function find(int $id): ?Department
    {
        return Department::query()->with(['parent', 'manager'])->find($id);
    }

    public function create(array $data): Department
    {
        return Department::create($data);
    }

    public function update(Department $department, array $data): Department
    {
        $department->update($data);

        return $department->fresh();
    }

    public function hierarchy(int $rootId): Collection
    {
        $root = Department::query()->with('children')->find($rootId);

        return $root ? collect([$root])->merge($this->expand($root)) : collect();
    }

    public function headcount(int $departmentId): int
    {
        return (int) Department::query()
            ->where('id', $departmentId)
            ->withCount('employees')
            ->first()?->employees_count;
    }

    private function expand(Department $department): Collection
    {
        $result = collect();
        foreach ($department->children as $child) {
            $result->push($child);
            $result = $result->merge($this->expand($child->load('children')));
        }

        return $result;
    }
}
