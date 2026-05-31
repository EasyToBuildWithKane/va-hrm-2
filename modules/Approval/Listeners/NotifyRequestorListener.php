<?php

declare(strict_types=1);

namespace Modules\Approval\Listeners;

use Modules\Approval\Events\ApprovalRejected;
use Modules\Approval\Events\ApprovalWorkflowCompleted;
use Modules\Notification\Services\NotificationService;

class NotifyRequestorListener
{
    public function __construct(private readonly NotificationService $service)
    {
    }

    public function handle(object $event): void
    {
        $workflow = property_exists($event, 'workflow') ? $event->workflow : null;
        if (! $workflow) {
            return;
        }

        $template = match (true) {
            $event instanceof ApprovalRejected => 'approval.rejected',
            $event instanceof ApprovalWorkflowCompleted => 'approval.completed',
            default => null,
        };

        if ($template === null) {
            return;
        }

        $user = $workflow->creator;
        if ($user === null) {
            return;
        }

        $this->service->notify(
            user: $user,
            template: $template,
            context: [
                'workflow_type' => $workflow->workflow_type,
                'workflow_id' => $workflow->id,
                'reason' => property_exists($event, 'reason') ? $event->reason : null,
            ],
            channels: ['in_app', 'email'],
        );
    }
}
