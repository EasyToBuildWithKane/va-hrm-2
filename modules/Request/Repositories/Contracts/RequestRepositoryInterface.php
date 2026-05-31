<?php

declare(strict_types=1);

namespace Modules\Request\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Request\Models\WorkflowRequest;

interface RequestRepositoryInterface
{
    /**
     * @param  array<string, mixed>  $filters
     */
    public function paginate(array $filters, int $perPage = 15): LengthAwarePaginator;

    public function findByUlid(string $ulid): ?WorkflowRequest;

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): WorkflowRequest;
}
