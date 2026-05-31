<?php

declare(strict_types=1);

namespace Modules\Employee\Actions;

use Illuminate\Contracts\Events\Dispatcher;
use Modules\Employee\Events\EmployeeOffboarded;
use Modules\Employee\Models\Employee;
use Modules\Employee\Services\EmployeeService;

final class OffboardEmployeeAction
{
    public function __construct(
        private readonly EmployeeService $service,
        private readonly Dispatcher $dispatcher,
    ) {
    }

    public function __invoke(Employee $employee): Employee
    {
        $employee = $this->service->completeOffboarding($employee);
        $this->dispatcher->dispatch(new EmployeeOffboarded($employee));

        return $employee;
    }
}
