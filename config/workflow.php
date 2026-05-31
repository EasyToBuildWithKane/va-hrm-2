<?php

declare(strict_types=1);

return [
    'default_sla_hours' => 24,

    'escalation' => [
        'enabled' => true,
        'check_interval_minutes' => 60,
        'default_escalate_to_role' => 'HR Director',
    ],

    'queues' => [
        'workflow' => 'default',
        'notifications' => 'notifications',
    ],

    'auto_approve' => [
        'enabled' => true,
    ],

    'allowed_workflow_types' => [
        'leave_request',
        'equipment_request',
        'account_request',
        'software_access_request',
        'reimbursement_request',
        'budget_proposal',
        'procurement_request',
        'expense_request',
        'salary_adjustment_proposal',
        'score_adjustment',
        'account_provisioning_request',
        'custom_approval_workflow',
    ],
];
