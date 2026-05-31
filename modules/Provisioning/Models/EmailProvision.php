<?php

declare(strict_types=1);

namespace Modules\Provisioning\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Employee\Models\Employee;

class EmailProvision extends Model
{
    protected $fillable = [
        'employee_id', 'account_provision_id',
        'email_address', 'alias', 'mailbox_type',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(AccountProvision::class, 'account_provision_id');
    }
}
