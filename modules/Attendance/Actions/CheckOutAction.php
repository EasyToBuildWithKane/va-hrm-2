<?php

declare(strict_types=1);

namespace Modules\Attendance\Actions;

use Modules\Attendance\Models\Attendance;
use Modules\Attendance\Services\AttendanceService;
use Modules\Employee\Models\Employee;

final class CheckOutAction
{
    public function __construct(private readonly AttendanceService $service)
    {
    }

    public function __invoke(Employee $employee, string $ip): Attendance
    {
        return $this->service->checkOut($employee, $ip);
    }
}
