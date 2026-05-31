<?php

declare(strict_types=1);

namespace Modules\Provisioning\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Employee\Models\Employee;

class AccessRevoked
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(public readonly Employee $employee, public readonly string $reason)
    {
    }
}
