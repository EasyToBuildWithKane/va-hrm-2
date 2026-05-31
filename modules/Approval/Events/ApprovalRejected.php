<?php

declare(strict_types=1);

namespace Modules\Approval\Events;

use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Approval\Models\ApprovalStep;
use Modules\Approval\Models\ApprovalWorkflow;

class ApprovalRejected
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public readonly ApprovalWorkflow $workflow,
        public readonly ApprovalStep $step,
        public readonly User $approver,
        public readonly string $reason,
    ) {
    }
}
