<?php

declare(strict_types=1);

namespace Modules\Permission\Services;

use App\Models\User;
use Illuminate\Support\Carbon;
use Modules\Permission\Models\PermissionDelegation;

class PermissionService
{
    /**
     * @return array<int, string>
     */
    public function effectivePermissions(User $user): array
    {
        return $user->getAllPermissions()->pluck('name')->all();
    }

    public function delegate(
        User $from,
        User $to,
        string $delegationType,
        string $reason,
        Carbon $validFrom,
        Carbon $validUntil,
        ?string $permission = null,
        ?int $roleId = null,
    ): PermissionDelegation {
        return PermissionDelegation::create([
            'delegated_by' => $from->id,
            'delegated_to' => $to->id,
            'delegation_type' => $delegationType,
            'role_id' => $roleId,
            'permission' => $permission,
            'valid_from' => $validFrom,
            'valid_until' => $validUntil,
            'reason' => $reason,
            'created_by' => $from->id,
        ]);
    }

    public function revokeDelegation(int $delegationId): bool
    {
        return (bool) PermissionDelegation::query()->whereKey($delegationId)->delete();
    }
}
