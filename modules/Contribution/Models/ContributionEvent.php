<?php

declare(strict_types=1);

namespace Modules\Contribution\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Employee\Models\Employee;

class ContributionEvent extends Model
{
    protected $fillable = [
        'employee_id', 'rule_id', 'event_type',
        'points_earned', 'reference_type', 'reference_id',
        'description', 'occurred_at',
    ];

    protected $casts = [
        'points_earned' => 'decimal:2',
        'occurred_at' => 'datetime',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function rule(): BelongsTo
    {
        return $this->belongsTo(ScoringRule::class, 'rule_id');
    }
}
