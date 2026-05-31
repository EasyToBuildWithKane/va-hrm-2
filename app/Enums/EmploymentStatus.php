<?php

declare(strict_types=1);

namespace App\Enums;

enum EmploymentStatus: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case ON_LEAVE = 'on_leave';
    case TERMINATED = 'terminated';
    case RESIGNED = 'resigned';
}
