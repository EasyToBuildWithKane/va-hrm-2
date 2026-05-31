<?php

declare(strict_types=1);

namespace Modules\Approval\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Approval\Models\ApprovalStep;
use Modules\Approval\Models\ApprovalWorkflow;

class ApprovalService
{
    public function pendingForUser(int $userId, int $perPage = 15): LengthAwarePaginator
    {
        return ApprovalStep::query()
            ->where('approver_id', $userId)
            ->where('status', 'pending')
            ->with(['workflow.requestable', 'workflow.creator'])
            ->orderBy('sla_deadline_at')
            ->paginate($perPage);
    }

    public function historyForUser(int $userId, int $perPage = 15): LengthAwarePaginator
    {
        return ApprovalStep::query()
            ->where(function ($q) use ($userId): void {
                $q->where('approver_id', $userId)->orWhere('delegated_to_id', $userId);
            })
            ->whereIn('status', ['approved', 'rejected', 'delegated'])
            ->with(['workflow'])
            ->latest('decision_at')
            ->paginate($perPage);
    }

    /**
     * @return array<string, int>
     */
    public function analytics(): array
    {
        return [
            'total_workflows' => ApprovalWorkflow::query()->count(),
            'in_progress' => ApprovalWorkflow::query()->where('status', 'in_progress')->count(),
            'approved' => ApprovalWorkflow::query()->where('status', 'approved')->count(),
            'rejected' => ApprovalWorkflow::query()->where('status', 'rejected')->count(),
            'escalated' => ApprovalWorkflow::query()->where('status', 'escalated')->count(),
            'overdue_steps' => ApprovalStep::query()
                ->where('status', 'pending')
                ->where('sla_deadline_at', '<', now())
                ->count(),
        ];
    }
}
