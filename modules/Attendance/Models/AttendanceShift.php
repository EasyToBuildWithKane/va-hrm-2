<?php

declare(strict_types=1);

namespace Modules\Attendance\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttendanceShift extends BaseModel
{
    use SoftDeletes;

    protected $table = 'attendance_shifts';

    protected $fillable = [
        'ulid', 'name', 'code',
        'start_time', 'end_time',
        'grace_minutes', 'break_minutes',
        'working_days', 'is_active',
    ];

    protected $casts = [
        'working_days' => 'array',
        'is_active' => 'boolean',
    ];
}
