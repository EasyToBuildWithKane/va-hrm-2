<?php

declare(strict_types=1);

return [
    'enabled' => true,

    'queue' => 'audit',

    'retention' => [
        'hot_table_years' => 2,
        'payroll_sensitive_years' => 7,
    ],

    'archive' => [
        'enabled' => true,
        'table' => 'audit_logs_archive',
        'cron' => '0 2 1 * *',
    ],

    'redact_placeholder' => '[REDACTED]',

    'request_context' => [
        'capture_ip' => true,
        'capture_user_agent' => true,
    ],
];
