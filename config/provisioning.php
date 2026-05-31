<?php

declare(strict_types=1);

return [
    'email_domain' => env('PROVISIONING_EMAIL_DOMAIN', 'company.com'),

    'username_strategy' => 'firstname.lastname',

    'offboarding' => [
        'email_suspend_then_disable_days' => 30,
        'revoke_licenses_immediately' => true,
        'revoke_roles_immediately' => true,
    ],

    'queue' => 'provisioning',
];
