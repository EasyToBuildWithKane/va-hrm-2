<?php

declare(strict_types=1);

namespace Modules\Audit\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Audit\Models\AuditLog;
use Modules\Audit\Repositories\Contracts\AuditRepositoryInterface;

class AuditRepository implements AuditRepositoryInterface
{
    public function paginate(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        return AuditLog::query()
            ->when($filters['event'] ?? null, fn ($q, $event) => $q->where('event', $event))
            ->when($filters['module'] ?? null, fn ($q, $module) => $q->where('auditable_type', 'like', "%\\{$module}\\%"))
            ->when($filters['performed_by'] ?? null, fn ($q, $performer) => $q->where('performed_by', $performer))
            ->when($filters['from'] ?? null, fn ($q, $from) => $q->where('created_at', '>=', $from))
            ->when($filters['to'] ?? null, fn ($q, $to) => $q->where('created_at', '<=', $to))
            ->latest('created_at')
            ->paginate($perPage);
    }

    public function forAuditable(string $type, int $id): array
    {
        return AuditLog::query()
            ->where('auditable_type', $type)
            ->where('auditable_id', $id)
            ->latest('created_at')
            ->get()
            ->all();
    }
}
