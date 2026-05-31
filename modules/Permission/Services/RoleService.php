<?php

declare(strict_types=1);

namespace Modules\Permission\Services;

use App\Models\User;
use Illuminate\Support\Collection;
use Modules\Permission\Models\Permission;
use Modules\Permission\Models\Role;

class RoleService
{
    /**
     * @return Collection<int, Role>
     */
    public function list(): Collection
    {
        return Role::with('permissions')->get();
    }

    /**
     * @param  array<int, string>  $permissions
     */
    public function create(string $name, array $permissions = []): Role
    {
        $role = Role::create(['name' => $name, 'guard_name' => 'web']);

        if ($permissions !== []) {
            $role->syncPermissions($permissions);
        }

        return $role;
    }

    /**
     * @param  array<int, string>  $permissions
     */
    public function syncPermissions(Role $role, array $permissions): Role
    {
        $role->syncPermissions($permissions);

        return $role->fresh('permissions');
    }

    public function assignToUser(User $user, string $roleName): User
    {
        $user->assignRole($roleName);

        return $user->fresh();
    }

    public function revokeFromUser(User $user, string $roleName): User
    {
        $user->removeRole($roleName);

        return $user->fresh();
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function buildMatrix(): array
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::query()->orderBy('name')->pluck('name')->toArray();

        $matrix = ['permissions' => $permissions, 'roles' => []];

        foreach ($roles as $role) {
            $matrix['roles'][$role->name] = $role->permissions->pluck('name')->toArray();
        }

        return $matrix;
    }
}
