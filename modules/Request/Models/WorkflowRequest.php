<?php

declare(strict_types=1);

namespace Modules\Request\Models;

use App\Concerns\HasAuditLog;
use App\Contracts\Auditable;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Approval\Models\ApprovalWorkflow;
use Modules\Employee\Models\Employee;

/**
 * @property int $id
 * @property string $ulid
 * @property string $request_type
 * @property int $employee_id
 * @property int|null $workflow_id
 * @property string $status
 * @property array $payload
 */
class WorkflowRequest extends BaseModel implements Auditable
{
    use HasAuditLog;
    use SoftDeletes;

    protected $fillable = [
        'ulid', 'request_type', 'employee_id', 'workflow_id',
        'status', 'payload', 'justification',
        'submitted_at', 'completed_at', 'cancelled_at', 'created_by',
    ];

    protected $casts = [
        'payload' => 'array',
        'submitted_at' => 'datetime',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    protected array $auditableFields = ['status', 'workflow_id', 'completed_at', 'cancelled_at'];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function workflow(): BelongsTo
    {
        return $this->belongsTo(ApprovalWorkflow::class);
    }

    public function equipment(): HasOne
    {
        return $this->hasOne(EquipmentRequest::class);
    }

    public function reimbursement(): HasOne
    {
        return $this->hasOne(ReimbursementRequest::class);
    }

    public function softwareAccess(): HasOne
    {
        return $this->hasOne(SoftwareAccessRequest::class);
    }

    public function account(): HasOne
    {
        return $this->hasOne(AccountRequest::class);
    }

    public function salaryAdjustment(): HasOne
    {
        return $this->hasOne(SalaryAdjustmentRequest::class);
    }
}
