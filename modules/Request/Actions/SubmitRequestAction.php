<?php

declare(strict_types=1);

namespace Modules\Request\Actions;

use App\Models\User;
use Modules\Request\DTOs\SubmitRequestDTO;
use Modules\Request\Models\WorkflowRequest;
use Modules\Request\Services\RequestService;

final class SubmitRequestAction
{
    public function __construct(private readonly RequestService $service)
    {
    }

    public function __invoke(SubmitRequestDTO $dto, User $submittedBy): WorkflowRequest
    {
        return $this->service->submit($dto, $submittedBy);
    }
}
