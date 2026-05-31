<?php

declare(strict_types=1);

namespace Modules\Employee\Actions;

use Illuminate\Contracts\Events\Dispatcher;
use Modules\Employee\DTOs\UpdateEmployeeDTO;
use Modules\Employee\Events\EmployeeUpdated;
use Modules\Employee\Models\Employee;
use Modules\Employee\Services\EmployeeService;

final class UpdateEmployeeAction
{
    public function __construct(
        private readonly EmployeeService $service,
        private readonly Dispatcher $dispatcher,
    ) {
    }

    public function __invoke(Employee $employee, UpdateEmployeeDTO $dto): Employee
    {
        $employee = $this->service->update($employee, $dto->changes);
        $this->dispatcher->dispatch(new EmployeeUpdated($employee, $dto->changes));

        return $employee;
    }
}
