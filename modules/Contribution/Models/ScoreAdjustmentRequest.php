<?php

declare(strict_types=1);

namespace Modules\Contribution\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Employee\Models\Employee;

class ScoreAdjustmentRequest extends BaseModel
{
    protected $fillable = [
        'ulid', 'employee_id', 'workflow_id',
        'adjustment_points', 'reason', 'status', 'requested_by',
    ];

    protected $casts = [
        'adjustment_points' => 'decimal:2',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
