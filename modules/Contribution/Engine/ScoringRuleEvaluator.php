<?php

declare(strict_types=1);

namespace Modules\Contribution\Engine;

use Illuminate\Database\Eloquent\Model;
use Modules\Contribution\Models\ScoringRule;
use Modules\Employee\Models\Employee;

class ScoringRuleEvaluator
{
    public function evaluate(ScoringRule $rule, Employee $employee, ?Model $source = null): bool
    {
        if (! $rule->is_active) {
            return false;
        }

        $conditions = $rule->conditions ?? [];
        if ($conditions === []) {
            return true;
        }

        foreach ($conditions as $field => $expected) {
            $value = match ($field) {
                'department_id' => $employee->department_id,
                'employment_type' => $employee->employment_type?->value ?? $employee->employment_type,
                'employment_status' => $employee->employment_status?->value ?? $employee->employment_status,
                default => null,
            };

            if ($value !== $expected) {
                return false;
            }
        }

        return true;
    }
}
