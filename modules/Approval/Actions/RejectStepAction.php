<?php

declare(strict_types=1);

namespace Modules\Approval\Actions;

use App\Models\User;
use Modules\Approval\Engine\ApprovalEngine;
use Modules\Approval\Models\ApprovalStep;
use Modules\Approval\Models\ApprovalWorkflow;

final class RejectStepAction
{
    public function __construct(private readonly ApprovalEngine $engine)
    {
    }

    public function __invoke(ApprovalStep $step, User $approver, string $reason): ApprovalWorkflow
    {
        return $this->engine->reject($step, $approver, $reason);
    }
}
