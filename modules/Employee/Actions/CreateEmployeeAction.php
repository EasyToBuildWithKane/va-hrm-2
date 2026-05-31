<?php

declare(strict_types=1);

namespace Modules\Employee\Actions;

use Illuminate\Contracts\Events\Dispatcher;
use Modules\Employee\DTOs\CreateEmployeeDTO;
use Modules\Employee\Events\EmployeeCreated;
use Modules\Employee\Models\Employee;
use Modules\Employee\Services\EmployeeService;

final class CreateEmployeeAction
{
    public function __construct(
        private readonly EmployeeService $service,
        private readonly Dispatcher $dispatcher,
    ) {
    }

    public function __invoke(CreateEmployeeDTO $dto): Employee
    {
        $employee = $this->service->create($dto);
        $this->dispatcher->dispatch(new EmployeeCreated($employee));

        return $employee;
    }
}
