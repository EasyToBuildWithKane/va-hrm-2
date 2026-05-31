<?php

declare(strict_types=1);

namespace Modules\Approval\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Approval\Actions\ApproveStepAction;
use Modules\Approval\Actions\DelegateApprovalAction;
use Modules\Approval\Actions\RejectStepAction;
use Modules\Approval\Engine\EscalationHandler;
use Modules\Approval\Models\ApprovalStep;
use Modules\Approval\Models\ApprovalWorkflow;
use Modules\Approval\Services\ApprovalService;

class ApprovalController extends Controller
{
    public function __construct(
        private readonly ApprovalService $service,
        private readonly ApproveStepAction $approveAction,
        private readonly RejectStepAction $rejectAction,
        private readonly DelegateApprovalAction $delegateAction,
        private readonly EscalationHandler $escalationHandler,
    ) {
    }

    public function queue(Request $request): JsonResponse
    {
        $paginated = $this->service->pendingForUser($request->user()->id, (int) $request->query('per_page', 15));

        return ApiResponse::success($paginated->items(), meta: [
            'total' => $paginated->total(),
            'per_page' => $paginated->perPage(),
            'current_page' => $paginated->currentPage(),
        ]);
    }

    public function history(Request $request): JsonResponse
    {
        $paginated = $this->service->historyForUser($request->user()->id, (int) $request->query('per_page', 15));

        return ApiResponse::success($paginated->items(), meta: [
            'total' => $paginated->total(),
            'per_page' => $paginated->perPage(),
            'current_page' => $paginated->currentPage(),
        ]);
    }

    public function show(ApprovalWorkflow $workflow): JsonResponse
    {
        $this->authorize('view', $workflow);

        return ApiResponse::success($workflow->load(['steps', 'requestable', 'creator']));
    }

    public function approve(Request $request, ApprovalWorkflow $workflow): JsonResponse
    {
        $this->authorize('approve', $workflow);

        $data = $request->validate(['notes' => ['nullable', 'string']]);

        $step = $workflow->currentStep();
        abort_unless($step !== null, 422, 'No active step');

        $workflow = ($this->approveAction)($step, $request->user(), $data['notes'] ?? null);

        return ApiResponse::success($workflow, 'Step approved');
    }

    public function reject(Request $request, ApprovalWorkflow $workflow): JsonResponse
    {
        $this->authorize('reject', $workflow);

        $data = $request->validate(['reason' => ['required', 'string']]);

        $step = $workflow->currentStep();
        abort_unless($step !== null, 422, 'No active step');

        $workflow = ($this->rejectAction)($step, $request->user(), $data['reason']);

        return ApiResponse::success($workflow, 'Step rejected');
    }

    public function delegate(Request $request, ApprovalWorkflow $workflow): JsonResponse
    {
        $this->authorize('delegate', $workflow);

        $data = $request->validate([
            'to_user_id' => ['required', 'exists:users,id'],
            'reason' => ['nullable', 'string'],
        ]);

        $step = $workflow->currentStep();
        abort_unless($step !== null, 422, 'No active step');

        $step = ($this->delegateAction)(
            $step,
            $request->user(),
            User::findOrFail($data['to_user_id']),
            $data['reason'] ?? null,
        );

        return ApiResponse::success($step, 'Approval delegated');
    }

    public function escalate(ApprovalWorkflow $workflow): JsonResponse
    {
        $step = $workflow->currentStep();
        abort_unless($step !== null, 422, 'No active step');

        $this->escalationHandler->escalate($step);

        return ApiResponse::message('Workflow escalated');
    }

    public function analytics(): JsonResponse
    {
        return ApiResponse::success($this->service->analytics());
    }
}
