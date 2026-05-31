<?php

declare(strict_types=1);

namespace Modules\Provisioning\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Employee\Models\Employee;

class ProvisioningLog extends Model
{
    protected $fillable = [
        'provisioning_request_id', 'employee_id',
        'action', 'subject', 'result', 'message', 'context',
    ];

    protected $casts = [
        'context' => 'array',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
