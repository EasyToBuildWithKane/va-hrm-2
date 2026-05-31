<?php

declare(strict_types=1);

namespace Modules\Department\Policies;

use App\Models\User;
use Modules\Department\Models\Department;

class DepartmentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('department.view');
    }

    public function view(User $user, Department $department): bool
    {
        if ($user->hasPermissionTo('department.view')) {
            return true;
        }

        return $user->employee?->department_id === $department->id;
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('department.create');
    }

    public function update(User $user, Department $department): bool
    {
        if ($user->hasPermissionTo('department.update')) {
            return true;
        }

        return $user->isManagerOf($department->id);
    }

    public function delete(User $user): bool
    {
        return $user->hasPermissionTo('department.delete');
    }
}
