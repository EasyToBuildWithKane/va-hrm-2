<?php

declare(strict_types=1);

namespace Modules\Approval\Listeners;

use Modules\Approval\Events\ApprovalRejected;
use Modules\Approval\Events\ApprovalWorkflowCompleted;
use Modules\Request\Models\WorkflowRequest;

class UpdateRequestStatusListener
{
    public function handle(object $event): void
    {
        $workflow = property_exists($event, 'workflow') ? $event->workflow : null;
        if (! $workflow) {
            return;
        }

        $requestable = $workflow->requestable;
        if (! $requestable instanceof WorkflowRequest) {
            return;
        }

        $status = match (true) {
            $event instanceof ApprovalWorkflowCompleted => 'approved',
            $event instanceof ApprovalRejected => 'rejected',
            default => $requestable->status,
        };

        $requestable->update([
            'status' => $status,
            'completed_at' => now(),
        ]);
    }
}
