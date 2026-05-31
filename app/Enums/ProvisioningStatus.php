<?php

declare(strict_types=1);

namespace App\Enums;

enum ProvisioningStatus: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case ACTIVE = 'active';
    case SUSPENDED = 'suspended';
    case DISABLED = 'disabled';
    case REVOKED = 'revoked';
}
