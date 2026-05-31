<?php

declare(strict_types=1);

namespace App\Enums;

enum WorkflowStatus: string
{
    case PENDING = 'pending';
    case IN_PROGRESS = 'in_progress';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
    case CANCELLED = 'cancelled';
    case ESCALATED = 'escalated';
}
