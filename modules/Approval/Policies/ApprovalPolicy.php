<?php

declare(strict_types=1);

namespace Modules\Approval\Policies;

use App\Models\User;
use Modules\Approval\Models\ApprovalWorkflow;

class ApprovalPolicy
{
    public function view(User $user, ApprovalWorkflow $workflow): bool
    {
        if ($user->hasPermissionTo('approval.view')) {
            return true;
        }

        return $workflow->created_by === $user->id;
    }

    public function approve(User $user): bool
    {
        return $user->hasPermissionTo('approval.approve')
            || $user->hasActiveDelegationFor('approval.approve');
    }

    public function reject(User $user): bool
    {
        return $user->hasPermissionTo('approval.reject')
            || $user->hasPermissionTo('approval.approve');
    }

    public function delegate(User $user): bool
    {
        return $user->hasPermissionTo('approval.delegate');
    }

    public function configure(User $user): bool
    {
        return $user->hasPermissionTo('approval.workflow.configure');
    }
}
