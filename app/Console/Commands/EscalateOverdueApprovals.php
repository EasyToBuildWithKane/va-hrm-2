<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modules\Approval\Engine\EscalationHandler;
use Modules\Approval\Models\ApprovalStep;

class EscalateOverdueApprovals extends Command
{
    protected $signature = 'approvals:escalate-overdue';

    protected $description = 'Escalate approval steps that have passed their SLA deadline';

    public function handle(EscalationHandler $handler): int
    {
        $overdue = ApprovalStep::query()
            ->where('status', 'pending')
            ->whereNotNull('sla_deadline_at')
            ->where('sla_deadline_at', '<', now())
            ->with('workflow')
            ->get();

        foreach ($overdue as $step) {
            $handler->escalate($step);
        }

        $this->info("Escalated {$overdue->count()} overdue step(s)");

        return self::SUCCESS;
    }
}
