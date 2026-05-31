<?php

declare(strict_types=1);

namespace Modules\Employee\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Employee\Models\Employee;

interface EmployeeRepositoryInterface
{
    /**
     * @param  array<string, mixed>  $filters
     */
    public function paginate(array $filters, int $perPage = 15): LengthAwarePaginator;

    public function find(int $id): ?Employee;

    public function findByUlid(string $ulid): ?Employee;

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): Employee;

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(Employee $employee, array $data): Employee;

    public function delete(Employee $employee): bool;

    public function emailExists(string $email, ?int $excludeId = null): bool;
}
