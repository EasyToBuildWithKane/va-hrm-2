<?php

declare(strict_types=1);

namespace Modules\Provisioning\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ProvisioningRepositoryInterface
{
    /**
     * @param  array<string, mixed>  $filters
     */
    public function paginateAccounts(array $filters, int $perPage = 15): LengthAwarePaginator;
}
