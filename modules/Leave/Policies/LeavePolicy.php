<?php

declare(strict_types=1);

namespace Modules\Leave\Policies;

use App\Models\User;
use Modules\Leave\Models\LeaveRequest;

class LeavePolicy
{
    public function view(User $user, LeaveRequest $request): bool
    {
        if ($user->hasPermissionTo('leave.request.view')) {
            return true;
        }

        return $user->id === $request->employee?->user_id
            || $user->isManagerOf($request->employee?->department_id ?? 0);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('leave.request.create');
    }

    public function approve(User $user, LeaveRequest $request): bool
    {
        return $user->hasPermissionTo('leave.request.approve')
            && $user->isManagerOf($request->employee?->department_id ?? 0);
    }

    public function cancel(User $user, LeaveRequest $request): bool
    {
        return $user->id === $request->employee?->user_id;
    }
}
