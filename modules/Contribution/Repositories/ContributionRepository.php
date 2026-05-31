<?php

declare(strict_types=1);

namespace Modules\Contribution\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Modules\Contribution\Models\ContributionEvent;
use Modules\Contribution\Models\ContributionScore;
use Modules\Contribution\Repositories\Contracts\ContributionRepositoryInterface;

class ContributionRepository implements ContributionRepositoryInterface
{
    public function ranking(int $perPage = 15, ?int $departmentId = null): LengthAwarePaginator
    {
        return ContributionScore::query()
            ->with('employee:id,ulid,first_name,last_name,department_id')
            ->when($departmentId, fn ($q, $id) => $q->whereHas('employee', fn ($qq) => $qq->where('department_id', $id)))
            ->orderByDesc('total_points')
            ->paginate($perPage);
    }

    public function eventsForEmployee(int $employeeId, int $limit = 50): Collection
    {
        return ContributionEvent::query()
            ->where('employee_id', $employeeId)
            ->with('rule')
            ->latest('occurred_at')
            ->limit($limit)
            ->get();
    }
}
