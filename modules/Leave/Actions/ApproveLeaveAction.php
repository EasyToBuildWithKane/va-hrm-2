<?php

declare(strict_types=1);

namespace Modules\Leave\Actions;

use Modules\Leave\Models\LeaveRequest;
use Modules\Leave\Services\LeaveService;

final class ApproveLeaveAction
{
    public function __construct(private readonly LeaveService $service)
    {
    }

    public function __invoke(LeaveRequest $request): LeaveRequest
    {
        return $this->service->approve($request);
    }
}
