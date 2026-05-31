<?php

declare(strict_types=1);

namespace Modules\Leave\Actions;

use App\Models\User;
use Modules\Employee\Models\Employee;
use Modules\Leave\Models\LeaveRequest;
use Modules\Leave\Services\LeaveService;

final class SubmitLeaveRequestAction
{
    public function __construct(private readonly LeaveService $service)
    {
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function __invoke(Employee $employee, array $data, User $submittedBy): LeaveRequest
    {
        return $this->service->submit($employee, $data, $submittedBy);
    }
}
