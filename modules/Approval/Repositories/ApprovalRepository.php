<?php

declare(strict_types=1);

namespace Modules\Approval\Repositories;

use Modules\Approval\Models\ApprovalWorkflow;
use Modules\Approval\Repositories\Contracts\ApprovalRepositoryInterface;

class ApprovalRepository implements ApprovalRepositoryInterface
{
    public function find(int $id): ?ApprovalWorkflow
    {
        return ApprovalWorkflow::query()->with(['steps', 'requestable', 'creator'])->find($id);
    }

    public function findByUlid(string $ulid): ?ApprovalWorkflow
    {
        return ApprovalWorkflow::query()->where('ulid', $ulid)->first();
    }
}
