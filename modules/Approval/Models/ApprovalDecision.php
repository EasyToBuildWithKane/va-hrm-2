<?php

declare(strict_types=1);

namespace Modules\Approval\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApprovalDecision extends Model
{
    protected $fillable = [
        'step_id', 'decided_by', 'decision',
        'notes', 'context', 'decided_at',
    ];

    protected $casts = [
        'context' => 'array',
        'decided_at' => 'datetime',
    ];

    public function step(): BelongsTo
    {
        return $this->belongsTo(ApprovalStep::class, 'step_id');
    }

    public function decider(): BelongsTo
    {
        return $this->belongsTo(User::class, 'decided_by');
    }
}
