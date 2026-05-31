<?php

declare(strict_types=1);

namespace Modules\Provisioning\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Employee\Models\Employee;

/**
 * @property string $account_identifier
 * @property string $account_type
 * @property string $status
 */
class AccountProvision extends Model
{
    protected $fillable = [
        'employee_id', 'provisioning_request_id', 'account_type',
        'account_identifier', 'status',
        'activated_at', 'suspended_at', 'revoked_at',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'activated_at' => 'datetime',
        'suspended_at' => 'datetime',
        'revoked_at' => 'datetime',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function provisioningRequest(): BelongsTo
    {
        return $this->belongsTo(ProvisioningRequest::class);
    }
}
