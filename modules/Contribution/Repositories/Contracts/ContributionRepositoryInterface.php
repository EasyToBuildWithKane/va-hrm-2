<?php

declare(strict_types=1);

namespace Modules\Contribution\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ContributionRepositoryInterface
{
    public function ranking(int $perPage = 15, ?int $departmentId = null): LengthAwarePaginator;

    public function eventsForEmployee(int $employeeId, int $limit = 50): Collection;
}
