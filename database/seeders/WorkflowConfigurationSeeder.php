<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Approval\Models\WorkflowConfiguration;

class WorkflowConfigurationSeeder extends Seeder
{
    public function run(): void
    {
        $configurations = [
            'leave_request' => [
                'steps' => [
                    ['step' => 1, 'label' => 'Manager Approval', 'approver_type' => 'manager', 'sla_hours' => 24, 'is_required' => true],
                    ['step' => 2, 'label' => 'HR Staff Review', 'approver_type' => 'role', 'approver_value' => 'HR Staff', 'sla_hours' => 48, 'is_required' => true],
                ],
                'escalation' => ['escalate_after_hours' => 48, 'escalate_to_role' => 'HR Director'],
            ],
            'equipment_request' => [
                'steps' => [
                    ['step' => 1, 'label' => 'Department Manager Approval', 'approver_type' => 'role', 'approver_value' => 'Department Manager', 'sla_hours' => 24, 'is_required' => true],
                    ['step' => 2, 'label' => 'HR Director Approval', 'approver_type' => 'role', 'approver_value' => 'HR Director', 'sla_hours' => 48, 'is_required' => true],
                    ['step' => 3, 'label' => 'IT Support Processing', 'approver_type' => 'role', 'approver_value' => 'IT Support', 'sla_hours' => 72, 'is_required' => true, 'is_action_step' => true],
                ],
                'escalation' => ['escalate_after_hours' => 48, 'escalate_to_role' => 'HR Director'],
            ],
            'account_provisioning_request' => [
                'steps' => [
                    ['step' => 1, 'label' => 'HR Approval', 'approver_type' => 'role', 'approver_value' => 'HR Staff', 'sla_hours' => 24, 'is_required' => true],
                    ['step' => 2, 'label' => 'IT Support Approval', 'approver_type' => 'role', 'approver_value' => 'IT Support', 'sla_hours' => 48, 'is_required' => true],
                ],
                'escalation' => ['escalate_after_hours' => 48, 'escalate_to_role' => 'HR Director'],
            ],
            'salary_adjustment_proposal' => [
                'steps' => [
                    ['step' => 1, 'label' => 'HR Director Approval', 'approver_type' => 'role', 'approver_value' => 'HR Director', 'sla_hours' => 48, 'is_required' => true],
                    ['step' => 2, 'label' => 'Finance Approval', 'approver_type' => 'role', 'approver_value' => 'Finance', 'sla_hours' => 48, 'is_required' => true],
                    ['step' => 3, 'label' => 'CEO Approval', 'approver_type' => 'role', 'approver_value' => 'Super Admin', 'sla_hours' => 72, 'is_required' => true],
                ],
                'escalation' => ['escalate_after_hours' => 72, 'escalate_to_role' => 'Super Admin'],
            ],
            'score_adjustment' => [
                'steps' => [
                    ['step' => 1, 'label' => 'HR Director Approval', 'approver_type' => 'role', 'approver_value' => 'HR Director', 'sla_hours' => 48, 'is_required' => true],
                ],
            ],
            'reimbursement_request' => [
                'steps' => [
                    ['step' => 1, 'label' => 'Department Manager Approval', 'approver_type' => 'manager', 'sla_hours' => 24, 'is_required' => true],
                    ['step' => 2, 'label' => 'Finance Approval', 'approver_type' => 'role', 'approver_value' => 'Finance', 'sla_hours' => 48, 'is_required' => true],
                ],
            ],
            'software_access_request' => [
                'steps' => [
                    ['step' => 1, 'label' => 'Manager Approval', 'approver_type' => 'manager', 'sla_hours' => 24, 'is_required' => true],
                    ['step' => 2, 'label' => 'IT Support Approval', 'approver_type' => 'role', 'approver_value' => 'IT Support', 'sla_hours' => 48, 'is_required' => true],
                ],
            ],
            'account_request' => [
                'steps' => [
                    ['step' => 1, 'label' => 'HR Approval', 'approver_type' => 'role', 'approver_value' => 'HR Staff', 'sla_hours' => 24, 'is_required' => true],
                    ['step' => 2, 'label' => 'IT Support Setup', 'approver_type' => 'role', 'approver_value' => 'IT Support', 'sla_hours' => 48, 'is_required' => true],
                ],
            ],
        ];

        foreach ($configurations as $type => $config) {
            WorkflowConfiguration::updateOrCreate(
                ['workflow_type' => $type],
                ['config' => $config, 'is_active' => true],
            );
        }
    }
}
