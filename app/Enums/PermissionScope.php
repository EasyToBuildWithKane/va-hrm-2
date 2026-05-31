<?php

declare(strict_types=1);

namespace App\Enums;

enum PermissionScope: string
{
    case ORGANIZATION = 'organization';
    case DEPARTMENT = 'department';
    case TEAM = 'team';
    case OWN = 'own';
}
