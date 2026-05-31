<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'employee.view', 'employee.view.own', 'employee.create', 'employee.update',
            'employee.delete', 'employee.restore', 'employee.salary.view',
            'employee.contract.manage', 'employee.document.manage',

            'department.view', 'department.create', 'department.update', 'department.delete',
            'department.manage', 'department.analytics.view',

            'leave.request.create', 'leave.request.view', 'leave.request.approve',
            'leave.quota.view', 'leave.quota.adjust', 'leave.policy.manage',

            'approval.view', 'approval.approve', 'approval.reject', 'approval.delegate',
            'approval.workflow.configure',

            'provisioning.view', 'provisioning.account.manage', 'provisioning.license.manage',
            'provisioning.offboarding.execute',

            'audit.view', 'audit.export', 'audit.employee.view',

            'contribution.view', 'contribution.score.adjust', 'contribution.rules.manage',

            'permission.role.manage', 'permission.delegate', 'permission.matrix.view',
        ];

        foreach ($permissions as $name) {
            Permission::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
        }
    }
}
