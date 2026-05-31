<?php

declare(strict_types=1);

namespace Modules\Provisioning\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Employee\Models\Employee;

class SoftwareLicense extends Model
{
    protected $fillable = [
        'name', 'vendor', 'license_key',
        'total_seats', 'used_seats', 'expires_at', 'metadata',
    ];

    protected $casts = [
        'expires_at' => 'date',
        'metadata' => 'array',
    ];

    public function employees(): BelongsToMany
    {
        return $this->belongsToMany(Employee::class, 'employee_software_licenses')
            ->withPivot(['assigned_at', 'revoked_at', 'assigned_by'])
            ->withTimestamps();
    }
}
