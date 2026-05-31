<?php

declare(strict_types=1);

namespace Modules\Audit\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface AuditRepositoryInterface
{
    /**
     * @param  array<string, mixed>  $filters
     */
    public function paginate(array $filters, int $perPage = 15): LengthAwarePaginator;

    /**
     * @return array<int, \Modules\Audit\Models\AuditLog>
     */
    public function forAuditable(string $type, int $id): array;
}
