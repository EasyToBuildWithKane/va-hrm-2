<?php

declare(strict_types=1);

namespace Modules\Audit\Services;

use App\Enums\AuditEvent;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Modules\Approval\Models\ApprovalStep;
use Modules\Audit\Models\AuditLog;

class AuditService
{
    /**
     * @param  array<string, mixed>|null  $oldValues
     * @param  array<string, mixed>|null  $newValues
     * @param  array<int, string>  $changedFields
     * @param  array<string, mixed>|null  $context
     */
    public function log(
        Model $auditable,
        AuditEvent $event,
        ?array $oldValues = null,
        ?array $newValues = null,
        array $changedFields = [],
        ?array $context = null,
        bool $payrollSensitive = false,
    ): AuditLog {
        return AuditLog::create([
            'ulid' => (string) Str::ulid(),
            'auditable_type' => $auditable::class,
            'auditable_id' => $auditable->getKey(),
            'event' => $event->value,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'changed_fields' => $changedFields,
            'performed_by' => Auth::id() ?? 0,
            'ip_address' => app()->has('audit.request.context') ? (app('audit.request.context')['ip'] ?? null) : null,
            'user_agent' => app()->has('audit.request.context') ? (app('audit.request.context')['user_agent'] ?? null) : null,
            'context' => $context,
            'payroll_sensitive' => $payrollSensitive,
            'created_at' => now(),
        ]);
    }

    public function logApprovalDecision(ApprovalStep $step, string $decision, User $approver): AuditLog
    {
        return $this->log(
            auditable: $step->workflow->requestable ?? $step->workflow,
            event: $decision === 'approved' ? AuditEvent::APPROVED : AuditEvent::REJECTED,
            oldValues: ['approval_status' => 'pending'],
            newValues: ['approval_status' => $decision],
            context: [
                'workflow_id' => $step->workflow_id,
                'step_number' => $step->step_number,
                'approver_id' => $approver->id,
                'notes' => $step->notes,
            ],
        );
    }

    /**
     * @return array<string, array{before: mixed, after: mixed, type: string}>
     */
    public function buildDiff(AuditLog $log): array
    {
        return DiffService::compute($log->old_values ?? [], $log->new_values ?? []);
    }
}
