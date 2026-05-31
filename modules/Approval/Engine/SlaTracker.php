<?php

declare(strict_types=1);

namespace Modules\Approval\Engine;

use Modules\Approval\Models\ApprovalWorkflow;

class SlaTracker
{
    public function setDeadlines(ApprovalWorkflow $workflow): void
    {
        $totalHours = (int) $workflow->steps()->sum('sla_hours');
        $workflow->update(['sla_deadline_at' => now()->addHours(max($totalHours, 1))]);
    }
}
