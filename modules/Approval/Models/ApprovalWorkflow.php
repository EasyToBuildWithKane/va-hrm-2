<?php

declare(strict_types=1);

namespace Modules\Approval\Models;

use App\Enums\WorkflowStatus;
use App\Models\BaseModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property int $id
 * @property string $ulid
 * @property string $requestable_type
 * @property int $requestable_id
 * @property string $workflow_type
 * @property int $current_step
 * @property int $total_steps
 * @property WorkflowStatus|string $status
 */
class ApprovalWorkflow extends BaseModel
{
    protected $fillable = [
        'ulid', 'requestable_type', 'requestable_id',
        'workflow_type', 'current_step', 'total_steps',
        'status', 'sla_deadline_at', 'completed_at',
        'created_by',
    ];

    protected $casts = [
        'status' => WorkflowStatus::class,
        'sla_deadline_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function requestable(): MorphTo
    {
        return $this->morphTo();
    }

    public function steps(): HasMany
    {
        return $this->hasMany(ApprovalStep::class, 'workflow_id')->orderBy('step_number');
    }

    public function decisions(): HasMany
    {
        return $this->hasManyThrough(ApprovalDecision::class, ApprovalStep::class, 'workflow_id', 'step_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function currentStep(): ?ApprovalStep
    {
        return $this->steps()->where('step_number', $this->current_step)->first();
    }

    public function getConfig(): array
    {
        return WorkflowConfiguration::getActiveConfig($this->workflow_type)->config;
    }
}
