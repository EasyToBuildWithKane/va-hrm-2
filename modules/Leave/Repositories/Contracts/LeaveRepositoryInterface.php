<?php

declare(strict_types=1);

namespace Modules\Leave\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Leave\Models\LeaveQuota;
use Modules\Leave\Models\LeaveRequest;

interface LeaveRepositoryInterface
{
    /**
     * @param  array<string, mixed>  $filters
     */
    public function paginate(array $filters, int $perPage = 15): LengthAwarePaginator;

    public function findRequest(int $id): ?LeaveRequest;

    /**
     * @return iterable<int, LeaveQuota>
     */
    public function quotasForEmployee(int $employeeId, int $year): iterable;
}
