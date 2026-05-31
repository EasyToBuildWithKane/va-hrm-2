<?php

declare(strict_types=1);

namespace Modules\Request\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Request\Models\WorkflowRequest;
use Modules\Request\Repositories\Contracts\RequestRepositoryInterface;

class RequestRepository implements RequestRepositoryInterface
{
    public function paginate(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        return WorkflowRequest::query()
            ->with(['employee:id,ulid,first_name,last_name', 'workflow'])
            ->when($filters['request_type'] ?? null, fn ($q, $type) => $q->where('request_type', $type))
            ->when($filters['status'] ?? null, fn ($q, $status) => $q->where('status', $status))
            ->when($filters['employee_id'] ?? null, fn ($q, $id) => $q->where('employee_id', $id))
            ->when($filters['from'] ?? null, fn ($q, $from) => $q->where('created_at', '>=', $from))
            ->when($filters['to'] ?? null, fn ($q, $to) => $q->where('created_at', '<=', $to))
            ->latest()
            ->paginate($perPage);
    }

    public function findByUlid(string $ulid): ?WorkflowRequest
    {
        return WorkflowRequest::query()
            ->with(['workflow.steps', 'employee', 'equipment', 'reimbursement', 'softwareAccess', 'account', 'salaryAdjustment'])
            ->where('ulid', $ulid)
            ->first();
    }

    public function create(array $data): WorkflowRequest
    {
        return WorkflowRequest::create($data);
    }
}
