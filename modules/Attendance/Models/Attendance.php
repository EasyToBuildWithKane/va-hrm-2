<?php

declare(strict_types=1);

namespace Modules\Attendance\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Employee\Models\Employee;

/**
 * @property int $employee_id
 * @property \Illuminate\Support\Carbon $date
 * @property \Illuminate\Support\Carbon|null $check_in_at
 * @property \Illuminate\Support\Carbon|null $check_out_at
 * @property string $status
 */
class Attendance extends Model
{
    protected $table = 'attendance_records';

    protected $fillable = [
        'employee_id', 'shift_id', 'date',
        'check_in_at', 'check_out_at',
        'check_in_ip', 'check_out_ip',
        'status', 'late_minutes', 'overtime_minutes',
        'notes', 'is_corrected',
    ];

    protected $casts = [
        'date' => 'date',
        'check_in_at' => 'datetime',
        'check_out_at' => 'datetime',
        'is_corrected' => 'boolean',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function shift(): BelongsTo
    {
        return $this->belongsTo(AttendanceShift::class, 'shift_id');
    }
}
