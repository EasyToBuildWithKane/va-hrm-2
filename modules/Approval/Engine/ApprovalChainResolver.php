<?php

declare(strict_types=1);

namespace Modules\Approval\Engine;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Modules\Approval\Models\WorkflowConfiguration;
use Modules\Employee\Models\Employee;

class ApprovalChainResolver
{
    /**
     * @return array<int, array<string, mixed>>
     */
    public function resolve(WorkflowConfiguration $config, Model $requestable, User $requester): array
    {
        $steps = [];

        foreach ($config->config['steps'] ?? [] as $stepConfig) {
            if ($this->shouldSkipStep($stepConfig, $requestable, $requester)) {
                continue;
            }

            $approver = $this->resolveApprover($stepConfig, $requester);

            $steps[] = [
                'step_number' => $stepConfig['step'],
                'approver_id' => $approver?->id,
                'approver_role' => $stepConfig['approver_value'] ?? null,
                'sla_hours' => $stepConfig['sla_hours'] ?? (int) config('workflow.default_sla_hours', 24),
                'sla_deadline_at' => now()->addHours($stepConfig['sla_hours'] ?? 24),
                'status' => 'pending',
            ];
        }

        return $steps;
    }

    /**
     * @param  array<string, mixed>  $stepConfig
     */
    private function resolveApprover(array $stepConfig, User $requester): ?User
    {
        return match ($stepConfig['approver_type'] ?? 'role') {
            'user' => isset($stepConfig['approver_id']) ? User::find($stepConfig['approver_id']) : null,
            'role' => $this->findUserByRole($stepConfig['approver_value']),
            'manager' => $this->findDirectManager($requester),
            'department' => $this->findDepartmentHead($requester),
            default => null,
        };
    }

    private function findUserByRole(string $roleName): ?User
    {
        return User::query()->role($roleName)->first();
    }

    private function findDirectManager(User $user): ?User
    {
        $employee = $user->employee;
        if (! $employee || ! $employee->manager_id) {
            return null;
        }

        return Employee::query()->find($employee->manager_id)?->user;
    }

    private function findDepartmentHead(User $user): ?User
    {
        $employee = $user->employee;
        if (! $employee) {
            return null;
        }

        return $employee->department?->manager?->user;
    }

    /**
     * @param  array<string, mixed>  $stepConfig
     */
    private function shouldSkipStep(array $stepConfig, Model $requestable, User $requester): bool
    {
        $skipRule = $stepConfig['skip_if'] ?? null;

        if ($skipRule === 'requester_is_manager') {
            return (bool) $requester->employee?->directReports()->exists();
        }

        return false;
    }
}
