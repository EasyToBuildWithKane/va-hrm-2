<?php

declare(strict_types=1);

namespace Modules\Leave\Services;

use App\Exceptions\WorkflowException;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Approval\Engine\ApprovalEngine;
use Modules\Employee\Models\Employee;
use Modules\Leave\Models\LeaveRequest;
use Modules\Leave\Models\LeaveType;

class LeaveService
{
    public function __construct(
        private readonly ApprovalEngine $approvalEngine,
        private readonly LeaveQuotaService $quotaService,
    ) {
    }

    /**
     * @param  array{leave_type_id: int, start_date: string, end_date: string, reason?: string|null, attachments?: array|null}  $data
     */
    public function submit(Employee $employee, array $data, User $submittedBy): LeaveRequest
    {
        return DB::transaction(function () use ($employee, $data, $submittedBy): LeaveRequest {
            $leaveType = LeaveType::query()->findOrFail($data['leave_type_id']);
            $start = Carbon::parse($data['start_date']);
            $end = Carbon::parse($data['end_date']);

            if ($end->lt($start)) {
                throw new WorkflowException('end_date must be on or after start_date', 'LEAVE_DATE_INVALID', 422);
            }

            $days = $start->diffInDays($end) + 1;

            $request = LeaveRequest::create([
                'ulid' => (string) Str::ulid(),
                'employee_id' => $employee->id,
                'leave_type_id' => $leaveType->id,
                'start_date' => $start,
                'end_date' => $end,
                'days_count' => $days,
                'reason' => $data['reason'] ?? null,
                'attachments' => $data['attachments'] ?? null,
                'status' => 'pending',
                'created_by' => $submittedBy->id,
            ]);

            $workflow = $this->approvalEngine->initiate($request, 'leave_request', $submittedBy);
            $request->update(['workflow_id' => $workflow->id]);

            return $request->fresh();
        });
    }

    public function approve(LeaveRequest $request): LeaveRequest
    {
        return DB::transaction(function () use ($request): LeaveRequest {
            $request->update(['status' => 'approved', 'approved_at' => now()]);
            $this->quotaService->deductDays($request->employee, $request->leaveType, (float) $request->days_count);

            return $request->fresh();
        });
    }

    public function reject(LeaveRequest $request): LeaveRequest
    {
        $request->update(['status' => 'rejected']);

        return $request->fresh();
    }

    public function cancel(LeaveRequest $request): LeaveRequest
    {
        if ($request->status === 'approved') {
            $this->quotaService->refundDays($request->employee, $request->leaveType, (float) $request->days_count);
        }

        $request->update(['status' => 'cancelled', 'cancelled_at' => now()]);

        return $request->fresh();
    }
}
