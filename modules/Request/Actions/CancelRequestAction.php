<?php

declare(strict_types=1);

namespace Modules\Request\Actions;

use Modules\Request\Models\WorkflowRequest;
use Modules\Request\Services\RequestService;

final class CancelRequestAction
{
    public function __construct(private readonly RequestService $service)
    {
    }

    public function __invoke(WorkflowRequest $request): WorkflowRequest
    {
        return $this->service->cancel($request);
    }
}
