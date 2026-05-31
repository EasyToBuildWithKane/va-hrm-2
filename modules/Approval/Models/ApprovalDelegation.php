<?php

declare(strict_types=1);

namespace Modules\Approval\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApprovalDelegation extends Model
{
    protected $fillable = [
        'step_id', 'from_user_id', 'to_user_id', 'reason', 'delegated_at',
    ];

    protected $casts = [
        'delegated_at' => 'datetime',
    ];

    public function step(): BelongsTo
    {
        return $this->belongsTo(ApprovalStep::class);
    }

    public function fromUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function toUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }
}
