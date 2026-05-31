<?php

declare(strict_types=1);

namespace Modules\Employee\Actions;

use Illuminate\Contracts\Events\Dispatcher;
use Modules\Employee\Events\EmployeeTerminated;
use Modules\Employee\Models\Employee;
use Modules\Employee\Services\EmployeeService;

final class TerminateEmployeeAction
{
    public function __construct(
        private readonly EmployeeService $service,
        private readonly Dispatcher $dispatcher,
    ) {
    }

    public function __invoke(Employee $employee, string $reason, ?string $effectiveDate = null): Employee
    {
        $employee = $this->service->terminate($employee, $reason, $effectiveDate);
        $this->dispatcher->dispatch(new EmployeeTerminated($employee, $reason));

        return $employee;
    }
}
