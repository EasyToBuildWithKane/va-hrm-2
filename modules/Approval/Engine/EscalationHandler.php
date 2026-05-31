<?php

declare(strict_types=1);

namespace Modules\Approval\Engine;

use App\Models\User;
use Modules\Approval\Events\ApprovalEscalated;
use Modules\Approval\Models\ApprovalStep;

class EscalationHandler
{
    public function escalate(ApprovalStep $step): void
    {
        $config = $step->workflow->getConfig();
        $escalateTo = $this->resolveEscalationTarget($config);

        if (! $escalateTo) {
            return;
        }

        $step->update(['status' => 'escalated']);

        $newStep = ApprovalStep::create([
            'workflow_id' => $step->workflow_id,
            'step_number' => $step->step_number,
            'approver_id' => $escalateTo->id,
            'status' => 'pending',
            'sla_hours' => (int) ($config['escalation']['sla_hours'] ?? 24),
            'sla_deadline_at' => now()->addHours((int) ($config['escalation']['sla_hours'] ?? 24)),
        ]);

        event(new ApprovalEscalated($newStep, $escalateTo));
    }

    /**
     * @param  array<string, mixed>  $config
     */
    private function resolveEscalationTarget(array $config): ?User
    {
        $role = $config['escalation']['escalate_to_role'] ?? config('workflow.escalation.default_escalate_to_role');

        if (! $role) {
            return null;
        }

        return User::query()->role($role)->first();
    }
}
