<?php

declare(strict_types=1);

namespace Modules\Leave\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Employee\Models\Employee;

class LeaveQuota extends Model
{
    protected $fillable = [
        'employee_id', 'leave_type_id', 'year',
        'entitled_days', 'used_days', 'carried_days',
    ];

    protected $casts = [
        'entitled_days' => 'decimal:2',
        'used_days' => 'decimal:2',
        'carried_days' => 'decimal:2',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function leaveType(): BelongsTo
    {
        return $this->belongsTo(LeaveType::class);
    }

    public function remainingDays(): float
    {
        return (float) ($this->entitled_days + $this->carried_days - $this->used_days);
    }
}
