<?php

declare(strict_types=1);

namespace Modules\Audit\Listeners;

use Modules\Approval\Events\ApprovalStepCompleted;
use Modules\Audit\Services\AuditService;

class AuditApprovalDecisionListener
{
    public function __construct(private readonly AuditService $auditService)
    {
    }

    public function handle(ApprovalStepCompleted $event): void
    {
        $this->auditService->logApprovalDecision($event->step, $event->decision, $event->approver);
    }
}
