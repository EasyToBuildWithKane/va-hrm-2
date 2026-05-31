<?php

declare(strict_types=1);

namespace Modules\Attendance\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Approval\Models\ApprovalWorkflow;
use Modules\Employee\Models\Employee;

class AttendanceCorrection extends BaseModel
{
    protected $fillable = [
        'ulid', 'attendance_record_id', 'employee_id', 'workflow_id',
        'proposed_values', 'reason', 'status',
    ];

    protected $casts = [
        'proposed_values' => 'array',
    ];

    public function attendance(): BelongsTo
    {
        return $this->belongsTo(Attendance::class, 'attendance_record_id');
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function workflow(): BelongsTo
    {
        return $this->belongsTo(ApprovalWorkflow::class);
    }
}
