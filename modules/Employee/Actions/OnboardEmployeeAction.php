<?php

declare(strict_types=1);

namespace Modules\Employee\Actions;

use Illuminate\Contracts\Events\Dispatcher;
use Modules\Employee\Events\EmployeeOnboarded;
use Modules\Employee\Models\Employee;
use Modules\Employee\Services\EmployeeService;

final class OnboardEmployeeAction
{
    public function __construct(
        private readonly EmployeeService $service,
        private readonly Dispatcher $dispatcher,
    ) {
    }

    public function __invoke(Employee $employee): Employee
    {
        $employee = $this->service->completeOnboarding($employee);
        $this->dispatcher->dispatch(new EmployeeOnboarded($employee));

        return $employee;
    }
}
