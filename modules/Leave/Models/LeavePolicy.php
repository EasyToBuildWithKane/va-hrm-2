<?php

declare(strict_types=1);

namespace Modules\Leave\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Department\Models\Department;

class LeavePolicy extends Model
{
    protected $fillable = ['leave_type_id', 'department_id', 'rules', 'is_active'];

    protected $casts = [
        'rules' => 'array',
        'is_active' => 'boolean',
    ];

    public function leaveType(): BelongsTo
    {
        return $this->belongsTo(LeaveType::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
}
