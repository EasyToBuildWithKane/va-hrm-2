<?php

declare(strict_types=1);

return [
    'roles' => [
        'super_admin' => 'Super Admin',
        'hr_director' => 'HR Director',
        'hr_staff' => 'HR Staff',
        'department_manager' => 'Department Manager',
        'team_leader' => 'Team Leader',
        'employee' => 'Employee',
        'it_support' => 'IT Support',
        'finance' => 'Finance',
        'auditor' => 'Auditor',
    ],

    'scopes' => [
        'organization',
        'department',
        'team',
        'own',
    ],

    'delegation' => [
        'max_duration_days' => 90,
    ],
];
