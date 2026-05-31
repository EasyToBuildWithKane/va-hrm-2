<?php

declare(strict_types=1);

namespace Modules\Attendance\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Attendance\Models\Attendance;

interface AttendanceRepositoryInterface
{
    /**
     * @param  array<string, mixed>  $filters
     */
    public function paginate(array $filters, int $perPage = 15): LengthAwarePaginator;

    public function todayFor(int $employeeId): ?Attendance;
}
