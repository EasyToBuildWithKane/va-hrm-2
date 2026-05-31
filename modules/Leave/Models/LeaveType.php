<?php

declare(strict_types=1);

namespace Modules\Leave\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    protected $fillable = [
        'name', 'code', 'days_per_year', 'is_paid',
        'carry_forward', 'max_carry_days', 'requires_docs',
        'min_notice_days', 'is_active',
    ];

    protected $casts = [
        'days_per_year' => 'decimal:2',
        'max_carry_days' => 'decimal:2',
        'is_paid' => 'boolean',
        'carry_forward' => 'boolean',
        'requires_docs' => 'boolean',
        'is_active' => 'boolean',
    ];
}
