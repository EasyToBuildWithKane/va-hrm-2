<?php

declare(strict_types=1);

namespace Modules\Approval\Actions;

use App\Models\User;
use Modules\Approval\Engine\ApprovalEngine;
use Modules\Approval\Models\ApprovalStep;

final class DelegateApprovalAction
{
    public function __construct(private readonly ApprovalEngine $engine)
    {
    }

    public function __invoke(ApprovalStep $step, User $from, User $to, ?string $reason = null): ApprovalStep
    {
        return $this->engine->delegate($step, $from, $to, $reason);
    }
}
