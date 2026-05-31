<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            'Super Admin' => null,
            'HR Director' => [
                'employee.view', 'employee.create', 'employee.update', 'employee.delete', 'employee.salary.view',
                'department.view', 'department.manage',
                'leave.request.approve', 'leave.policy.manage',
                'approval.approve', 'approval.workflow.configure',
                'provisioning.account.manage',
                'audit.view', 'audit.export',
                'contribution.rules.manage',
            ],
            'HR Staff' => [
                'employee.view', 'employee.update',
                'department.view',
                'leave.request.approve',
                'approval.approve',
            ],
            'Department Manager' => [
                'employee.view', 'department.view', 'department.manage',
                'leave.request.create', 'leave.request.approve',
                'approval.approve',
            ],
            'Team Leader' => [
                'employee.view', 'leave.request.create',
            ],
            'Employee' => [
                'employee.view.own', 'leave.request.create',
            ],
            'IT Support' => [
                'provisioning.account.manage', 'provisioning.license.manage',
                'approval.approve',
            ],
            'Finance' => [
                'employee.salary.view', 'approval.approve',
            ],
            'Auditor' => [
                'employee.view', 'department.view',
                'audit.view', 'audit.export',
            ],
        ];

        foreach ($roles as $name => $permissions) {
            $role = Role::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
            if ($permissions === null) {
                $role->syncPermissions(\Modules\Permission\Models\Permission::all());
            } else {
                $role->syncPermissions($permissions);
            }
        }
    }
}
