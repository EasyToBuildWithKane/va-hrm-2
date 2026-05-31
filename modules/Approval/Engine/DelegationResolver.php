<?php

declare(strict_types=1);

namespace Modules\Approval\Engine;

use App\Models\User;
use Modules\Approval\Models\ApprovalDelegation;
use Modules\Approval\Models\ApprovalStep;

class DelegationResolver
{
    public function delegate(ApprovalStep $step, User $from, User $to, ?string $reason = null): ApprovalStep
    {
        ApprovalDelegation::create([
            'step_id' => $step->id,
            'from_user_id' => $from->id,
            'to_user_id' => $to->id,
            'reason' => $reason,
            'delegated_at' => now(),
        ]);

        $step->update([
            'delegated_to_id' => $to->id,
            'approver_id' => $to->id,
            'status' => 'delegated',
        ]);

        return $step->fresh();
    }
}
