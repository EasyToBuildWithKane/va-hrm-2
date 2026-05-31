<?php

declare(strict_types=1);

namespace Modules\Employee\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Employee\Models\Employee;

class EmployeeUpdated
{
    use Dispatchable;
    use SerializesModels;

    /**
     * @param  array<string, mixed>  $changes
     */
    public function __construct(public readonly Employee $employee, public readonly array $changes = [])
    {
    }
}
