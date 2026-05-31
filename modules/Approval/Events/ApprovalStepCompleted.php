<?php

declare(strict_types=1);

namespace Modules\Approval\Events;

use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Approval\Models\ApprovalStep;

class ApprovalStepCompleted
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public readonly ApprovalStep $step,
        public readonly string $decision,
        public readonly User $approver,
    ) {
    }
}
