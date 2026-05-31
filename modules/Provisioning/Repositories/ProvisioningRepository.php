<?php

declare(strict_types=1);

namespace Modules\Provisioning\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Provisioning\Models\AccountProvision;
use Modules\Provisioning\Repositories\Contracts\ProvisioningRepositoryInterface;

class ProvisioningRepository implements ProvisioningRepositoryInterface
{
    public function paginateAccounts(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        return AccountProvision::query()
            ->with('employee:id,ulid,first_name,last_name')
            ->when($filters['employee_id'] ?? null, fn ($q, $id) => $q->where('employee_id', $id))
            ->when($filters['account_type'] ?? null, fn ($q, $type) => $q->where('account_type', $type))
            ->when($filters['status'] ?? null, fn ($q, $status) => $q->where('status', $status))
            ->latest()
            ->paginate($perPage);
    }
}
