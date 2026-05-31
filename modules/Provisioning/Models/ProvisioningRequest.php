<?php

declare(strict_types=1);

namespace Modules\Provisioning\Models;

use App\Models\BaseModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Approval\Models\ApprovalWorkflow;
use Modules\Employee\Models\Employee;

class ProvisioningRequest extends BaseModel
{
    protected $fillable = [
        'ulid', 'employee_id', 'workflow_id', 'type', 'status',
        'requested_by', 'processed_by', 'processed_at', 'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'processed_at' => 'datetime',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function workflow(): BelongsTo
    {
        return $this->belongsTo(ApprovalWorkflow::class);
    }

    public function requestedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function accounts(): HasMany
    {
        return $this->hasMany(AccountProvision::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(ProvisioningLog::class);
    }
}
