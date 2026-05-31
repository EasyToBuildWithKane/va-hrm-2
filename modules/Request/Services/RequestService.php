<?php

declare(strict_types=1);

namespace Modules\Request\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Modules\Approval\Engine\ApprovalEngine;
use Modules\Employee\Models\Employee;
use Modules\Request\DTOs\SubmitRequestDTO;
use Modules\Request\Models\AccountRequest;
use Modules\Request\Models\EquipmentRequest;
use Modules\Request\Models\ReimbursementRequest;
use Modules\Request\Models\SalaryAdjustmentRequest;
use Modules\Request\Models\SoftwareAccessRequest;
use Modules\Request\Models\WorkflowRequest;
use Modules\Request\Repositories\Contracts\RequestRepositoryInterface;

class RequestService
{
    public function __construct(
        private readonly RequestRepositoryInterface $repository,
        private readonly ApprovalEngine $approvalEngine,
    ) {
    }

    public function submit(SubmitRequestDTO $dto, User $submittedBy): WorkflowRequest
    {
        return DB::transaction(function () use ($dto, $submittedBy): WorkflowRequest {
            $request = $this->repository->create([
                'request_type' => $dto->requestType,
                'employee_id' => $dto->employeeId,
                'status' => 'pending',
                'payload' => $dto->payload,
                'justification' => $dto->justification,
                'submitted_at' => now(),
                'created_by' => $submittedBy->id,
            ]);

            $this->persistSpecific($request, $dto);

            $workflow = $this->approvalEngine->initiate($request, $dto->requestType, $submittedBy);
            $request->update(['workflow_id' => $workflow->id, 'status' => 'in_progress']);

            return $request->fresh();
        });
    }

    public function cancel(WorkflowRequest $request): WorkflowRequest
    {
        $request->update(['status' => 'cancelled', 'cancelled_at' => now()]);

        return $request->fresh();
    }

    private function persistSpecific(WorkflowRequest $request, SubmitRequestDTO $dto): void
    {
        $payload = $dto->payload;
        $base = ['workflow_request_id' => $request->id];

        match ($dto->requestType) {
            'equipment_request' => EquipmentRequest::create([
                ...$base,
                'equipment_type' => $payload['equipment_type'] ?? 'unknown',
                'model' => $payload['model'] ?? null,
                'quantity' => $payload['quantity'] ?? 1,
                'estimated_cost' => $payload['estimated_cost'] ?? null,
            ]),
            'reimbursement_request' => ReimbursementRequest::create([
                ...$base,
                'amount' => (float) ($payload['amount'] ?? 0),
                'currency' => $payload['currency'] ?? 'USD',
                'category' => $payload['category'] ?? 'misc',
                'expense_date' => $payload['expense_date'] ?? now()->toDateString(),
                'receipts' => $payload['receipts'] ?? null,
            ]),
            'software_access_request' => SoftwareAccessRequest::create([
                ...$base,
                'software_name' => $payload['software_name'] ?? 'unknown',
                'access_level' => $payload['access_level'] ?? null,
                'needed_by' => $payload['needed_by'] ?? null,
            ]),
            'account_request' => AccountRequest::create([
                ...$base,
                'account_type' => $payload['account_type'] ?? 'system',
                'access_scopes' => $payload['access_scopes'] ?? null,
            ]),
            'salary_adjustment_proposal' => SalaryAdjustmentRequest::create([
                ...$base,
                'target_employee_id' => $payload['target_employee_id'] ?? $request->employee_id,
                'current_salary' => (float) ($payload['current_salary'] ?? Employee::find($request->employee_id)?->salary ?? 0),
                'proposed_salary' => (float) ($payload['proposed_salary'] ?? 0),
                'effective_date' => $payload['effective_date'] ?? now()->toDateString(),
                'justification' => $payload['justification'] ?? null,
            ]),
            default => null,
        };
    }
}
