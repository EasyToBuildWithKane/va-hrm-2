<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Enabled Modules
    |--------------------------------------------------------------------------
    |
    | ModuleServiceProvider loads each entry as `Modules\{Name}\{Name}ServiceProvider`.
    | Order matters: dependencies (Permission, Audit) must be listed first.
    |
    */

    'enabled' => [
        'Permission',
        'Audit',
        'Notification',
        'Department',
        'Employee',
        'Organization',
        'Approval',
        'Request',
        'Attendance',
        'Leave',
        'Provisioning',
        'Contribution',
    ],

    'base_path' => base_path('modules'),
];
