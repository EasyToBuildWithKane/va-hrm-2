<?php

declare(strict_types=1);

namespace Modules\Approval\Repositories\Contracts;

use Modules\Approval\Models\ApprovalWorkflow;

interface ApprovalRepositoryInterface
{
    public function find(int $id): ?ApprovalWorkflow;

    public function findByUlid(string $ulid): ?ApprovalWorkflow;
}
