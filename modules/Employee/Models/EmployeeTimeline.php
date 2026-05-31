<?php

declare(strict_types=1);

namespace Modules\Employee\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeTimeline extends Model
{
    protected $table = 'employee_timeline';

    protected $fillable = [
        'employee_id', 'event_type', 'title',
        'description', 'payload', 'occurred_at', 'performed_by',
    ];

    protected $casts = [
        'payload' => 'array',
        'occurred_at' => 'datetime',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
