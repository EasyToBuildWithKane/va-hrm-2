<?php

declare(strict_types=1);

return [
    'weights' => [
        'task_completed' => 1.0,
        'project_delivered' => 2.0,
        'overtime_contribution' => 0.8,
        'peer_recognition' => 1.5,
        'dept_achievement' => 1.2,
        'approval_efficiency' => 1.0,
        'innovation_proposal' => 1.8,
        'leave_not_abused' => 1.0,
        'onboarding_complete' => 1.0,
        'training_completed' => 1.2,
    ],

    'decay' => [
        'enabled' => true,
        'half_life_days' => 180,
    ],

    'caps' => [
        'daily_max' => 100,
        'monthly_max' => 500,
    ],

    'ranking' => [
        'recalc_cron' => '0 0 * * *',
    ],
];
