<?php

declare(strict_types=1);

namespace Modules\Department\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Modules\Department\Models\Department;

interface DepartmentRepositoryInterface
{
    /**
     * @param  array<string, mixed>  $filters
     */
    public function paginate(array $filters, int $perPage = 15): LengthAwarePaginator;

    public function find(int $id): ?Department;

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): Department;

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(Department $department, array $data): Department;

    /**
     * @return Collection<int, Department>
     */
    public function hierarchy(int $rootId): Collection;

    public function headcount(int $departmentId): int;
}
