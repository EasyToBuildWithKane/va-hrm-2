<?php

declare(strict_types=1);

namespace Modules\Approval\Models;

use App\Enums\StepStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $workflow_id
 * @property int $step_number
 * @property int|null $approver_id
 * @property string|null $approver_role
 * @property StepStatus|string $status
 * @property \Illuminate\Support\Carbon|null $sla_deadline_at
 */
class ApprovalStep extends Model
{
    protected $fillable = [
        'workflow_id', 'step_number', 'approver_id', 'approver_role',
        'status', 'decision_at', 'notes', 'delegated_to_id',
        'sla_hours', 'sla_deadline_at',
    ];

    protected $casts = [
        'status' => StepStatus::class,
        'decision_at' => 'datetime',
        'sla_deadline_at' => 'datetime',
    ];

    public function workflow(): BelongsTo
    {
        return $this->belongsTo(ApprovalWorkflow::class, 'workflow_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

    public function delegatee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'delegated_to_id');
    }
}
