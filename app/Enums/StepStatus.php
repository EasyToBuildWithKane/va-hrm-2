<?php

declare(strict_types=1);

namespace App\Enums;

enum StepStatus: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
    case SKIPPED = 'skipped';
    case DELEGATED = 'delegated';
    case ESCALATED = 'escalated';
}
