<?php

declare(strict_types=1);

namespace Modules\Employee\Policies;

use App\Models\User;
use Modules\Employee\Models\Employee;

class EmployeePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('employee.view') || $user->hasPermissionTo('employee.view.own');
    }

    public function view(User $user, Employee $employee): bool
    {
        if ($user->hasPermissionTo('employee.view')) {
            return true;
        }

        if ($user->id === $employee->user_id) {
            return true;
        }

        return $user->isManagerOf($employee->department_id);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('employee.create');
    }

    public function update(User $user, Employee $employee): bool
    {
        if ($user->hasPermissionTo('employee.update')) {
            return true;
        }

        return $user->id === $employee->user_id;
    }

    public function delete(User $user): bool
    {
        return $user->hasPermissionTo('employee.delete');
    }

    public function restore(User $user): bool
    {
        return $user->hasPermissionTo('employee.restore');
    }

    public function viewSalary(User $user): bool
    {
        return $user->hasPermissionTo('employee.salary.view');
    }

    public function onboard(User $user): bool
    {
        return $user->hasPermissionTo('employee.update');
    }

    public function terminate(User $user): bool
    {
        return $user->hasPermissionTo('employee.delete');
    }
}
