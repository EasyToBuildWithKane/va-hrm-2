<?php

declare(strict_types=1);

namespace Modules\Approval\Engine;

use App\Enums\StepStatus;
use App\Enums\WorkflowStatus;
use App\Exceptions\WorkflowException;
use App\Models\User;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Approval\Events\ApprovalRejected;
use Modules\Approval\Events\ApprovalStepCompleted;
use Modules\Approval\Events\ApprovalWorkflowCompleted;
use Modules\Approval\Models\ApprovalDecision;
use Modules\Approval\Models\ApprovalStep;
use Modules\Approval\Models\ApprovalWorkflow;
use Modules\Approval\Models\WorkflowConfiguration;
use Modules\Notification\Services\NotificationService;

class ApprovalEngine
{
    public function __construct(
        private readonly ApprovalChainResolver $chainResolver,
        private readonly SlaTracker $slaTracker,
        private readonly DelegationResolver $delegationResolver,
        private readonly NotificationService $notificationService,
        private readonly Dispatcher $dispatcher,
    ) {
    }

    public function initiate(Model $requestable, string $workflowType, User $requestedBy): ApprovalWorkflow
    {
        $config = WorkflowConfiguration::getActiveConfig($workflowType);
        $chain = $this->chainResolver->resolve($config, $requestable, $requestedBy);

        if ($chain === []) {
            throw new WorkflowException('No approval steps generated for workflow', 'WORKFLOW_EMPTY_CHAIN');
        }

        return DB::transaction(function () use ($requestable, $workflowType, $requestedBy, $chain): ApprovalWorkflow {
            $workflow = ApprovalWorkflow::create([
                'ulid' => (string) Str::ulid(),
                'requestable_type' => $requestable::class,
                'requestable_id' => $requestable->getKey(),
                'workflow_type' => $workflowType,
                'status' => WorkflowStatus::IN_PROGRESS->value,
                'total_steps' => count($chain),
                'current_step' => 1,
                'created_by' => $requestedBy->id,
            ]);

            foreach ($chain as $stepData) {
                ApprovalStep::create([...$stepData, 'workflow_id' => $workflow->id]);
            }

            $this->slaTracker->setDeadlines($workflow);
            $this->notifyApprovers($workflow);

            return $workflow->fresh('steps');
        });
    }

    public function approve(ApprovalStep $step, User $approver, ?string $notes = null): ApprovalWorkflow
    {
        $this->authorize($step, $approver);

        $step->update([
            'status' => StepStatus::APPROVED->value,
            'decision_at' => now(),
            'notes' => $notes,
        ]);

        ApprovalDecision::create([
            'step_id' => $step->id,
            'decided_by' => $approver->id,
            'decision' => 'approve',
            'notes' => $notes,
            'decided_at' => now(),
        ]);

        $this->dispatcher->dispatch(new ApprovalStepCompleted($step, 'approved', $approver));

        $workflow = $step->workflow;

        if ($this->isLastStep($step)) {
            return $this->completeWorkflow($workflow);
        }

        return $this->advanceToNextStep($workflow);
    }

    public function reject(ApprovalStep $step, User $approver, string $reason): ApprovalWorkflow
    {
        $this->authorize($step, $approver);

        $step->update([
            'status' => StepStatus::REJECTED->value,
            'decision_at' => now(),
            'notes' => $reason,
        ]);

        ApprovalDecision::create([
            'step_id' => $step->id,
            'decided_by' => $approver->id,
            'decision' => 'reject',
            'notes' => $reason,
            'decided_at' => now(),
        ]);

        $workflow = $step->workflow;
        $workflow->update(['status' => WorkflowStatus::REJECTED->value, 'completed_at' => now()]);

        $this->dispatcher->dispatch(new ApprovalStepCompleted($step, 'rejected', $approver));
        $this->dispatcher->dispatch(new ApprovalRejected($workflow, $step, $approver, $reason));

        return $workflow->fresh();
    }

    public function delegate(ApprovalStep $step, User $from, User $to, ?string $reason = null): ApprovalStep
    {
        $this->authorize($step, $from);

        return $this->delegationResolver->delegate($step, $from, $to, $reason);
    }

    public function cancel(ApprovalWorkflow $workflow): ApprovalWorkflow
    {
        $workflow->update(['status' => WorkflowStatus::CANCELLED->value, 'completed_at' => now()]);

        return $workflow->fresh();
    }

    private function isLastStep(ApprovalStep $step): bool
    {
        return $step->step_number >= $step->workflow->total_steps;
    }

    private function completeWorkflow(ApprovalWorkflow $workflow): ApprovalWorkflow
    {
        $workflow->update([
            'status' => WorkflowStatus::APPROVED->value,
            'completed_at' => now(),
        ]);

        $this->dispatcher->dispatch(new ApprovalWorkflowCompleted($workflow->fresh()));

        return $workflow->fresh();
    }

    private function advanceToNextStep(ApprovalWorkflow $workflow): ApprovalWorkflow
    {
        $workflow->update(['current_step' => $workflow->current_step + 1]);
        $this->notifyApprovers($workflow->fresh('steps'));

        return $workflow->fresh();
    }

    private function authorize(ApprovalStep $step, User $approver): void
    {
        if ($step->approver_id && $step->approver_id !== $approver->id
            && ! $approver->hasActiveDelegationFor('approval.approve')) {
            throw WorkflowException::notAuthorized($step->workflow_id, $step->id, $step->approver_role);
        }
    }

    private function notifyApprovers(ApprovalWorkflow $workflow): void
    {
        $step = $workflow->currentStep();
        if (! $step || ! $step->approver_id) {
            return;
        }

        $approver = $step->approver;
        if ($approver === null) {
            return;
        }

        $this->notificationService->notify(
            user: $approver,
            template: 'approval.requested',
            context: [
                'workflow_type' => $workflow->workflow_type,
                'workflow_id' => $workflow->id,
                'step_id' => $step->id,
            ],
            channels: ['in_app', 'email'],
        );
    }
}
