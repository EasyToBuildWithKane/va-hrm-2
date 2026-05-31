<?php

declare(strict_types=1);

namespace Modules\Department\Services;

use Modules\Department\Models\Department;
use Modules\Department\Repositories\Contracts\DepartmentRepositoryInterface;

class DepartmentService
{
    public function __construct(private readonly DepartmentRepositoryInterface $repository)
    {
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): Department
    {
        return $this->repository->create($data);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(Department $department, array $data): Department
    {
        return $this->repository->update($department, $data);
    }

    /**
     * @return array{department_id: int, total_headcount: int, hierarchy_depth: int, active_employees: int}
     */
    public function analytics(Department $department): array
    {
        $hierarchy = $this->repository->hierarchy($department->id);

        return [
            'department_id' => $department->id,
            'total_headcount' => $hierarchy->sum(fn (Department $d) => $this->repository->headcount($d->id)),
            'hierarchy_depth' => $hierarchy->count(),
            'active_employees' => $department->employees()->where('employment_status', 'active')->count(),
        ];
    }
}
