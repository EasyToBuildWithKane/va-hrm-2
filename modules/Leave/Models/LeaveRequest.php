<?php

declare(strict_types=1);

namespace Modules\Leave\Models;

use App\Concerns\HasAuditLog;
use App\Contracts\Auditable;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Approval\Models\ApprovalWorkflow;
use Modules\Employee\Models\Employee;

/**
 * @property int $id
 * @property string $ulid
 * @property int $employee_id
 * @property int $leave_type_id
 * @property string $status
 */
class LeaveRequest extends BaseModel implements Auditable
{
    use HasAuditLog;
    use SoftDeletes;

    protected $fillable = [
        'ulid', 'employee_id', 'leave_type_id', 'workflow_id',
        'start_date', 'end_date', 'days_count', 'reason',
        'status', 'attachments', 'approved_at', 'cancelled_at', 'created_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'days_count' => 'decimal:2',
        'attachments' => 'array',
        'approved_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    protected array $auditableFields = ['status', 'start_date', 'end_date', 'days_count', 'approved_at'];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function leaveType(): BelongsTo
    {
        return $this->belongsTo(LeaveType::class);
    }

    public function workflow(): BelongsTo
    {
        return $this->belongsTo(ApprovalWorkflow::class);
    }
}
