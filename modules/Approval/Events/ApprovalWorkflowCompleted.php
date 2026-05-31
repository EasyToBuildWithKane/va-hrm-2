<?php

declare(strict_types=1);

namespace Modules\Approval\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Approval\Models\ApprovalWorkflow;

class ApprovalWorkflowCompleted
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(public readonly ApprovalWorkflow $workflow)
    {
    }
}
