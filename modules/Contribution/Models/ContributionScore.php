<?php

declare(strict_types=1);

namespace Modules\Contribution\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Employee\Models\Employee;

class ContributionScore extends Model
{
    protected $fillable = [
        'employee_id', 'total_points',
        'monthly_points', 'quarterly_points',
        'rank_overall', 'rank_department',
        'last_calculated_at',
    ];

    protected $casts = [
        'total_points' => 'decimal:2',
        'monthly_points' => 'decimal:2',
        'quarterly_points' => 'decimal:2',
        'last_calculated_at' => 'datetime',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
