<?php

declare(strict_types=1);

namespace Modules\Request\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Employee\Models\Employee;

class SalaryAdjustmentRequest extends Model
{
    protected $fillable = [
        'workflow_request_id', 'target_employee_id',
        'current_salary', 'proposed_salary', 'effective_date', 'justification',
    ];

    protected $casts = [
        'current_salary' => 'decimal:2',
        'proposed_salary' => 'decimal:2',
        'effective_date' => 'date',
    ];

    public function workflowRequest(): BelongsTo
    {
        return $this->belongsTo(WorkflowRequest::class);
    }

    public function targetEmployee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'target_employee_id');
    }
}
