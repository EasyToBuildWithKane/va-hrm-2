<?php

declare(strict_types=1);

namespace Modules\Attendance\Actions;

use Modules\Attendance\Models\Attendance;
use Modules\Attendance\Models\AttendanceCorrection;
use Modules\Attendance\Services\AttendanceService;

final class CorrectAttendanceAction
{
    public function __construct(private readonly AttendanceService $service)
    {
    }

    /**
     * @param  array<string, mixed>  $proposed
     */
    public function __invoke(Attendance $attendance, array $proposed, string $reason): AttendanceCorrection
    {
        return $this->service->submitCorrection($attendance, $proposed, $reason);
    }
}
