<?php

declare(strict_types=1);

namespace Modules\Employee\Models;

use App\Concerns\HasAuditLog;
use App\Contracts\Auditable;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeContract extends BaseModel implements Auditable
{
    use HasAuditLog;
    use SoftDeletes;

    protected $fillable = [
        'ulid', 'employee_id', 'contract_number',
        'contract_type', 'start_date', 'end_date',
        'base_salary', 'file_path', 'status',
        'metadata', 'created_by', 'updated_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'base_salary' => 'decimal:2',
        'metadata' => 'array',
    ];

    protected array $sensitiveFields = ['base_salary'];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
