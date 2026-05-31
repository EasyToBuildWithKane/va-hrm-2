<?php

declare(strict_types=1);

namespace Modules\Employee\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeDocument extends BaseModel
{
    use SoftDeletes;

    protected $fillable = [
        'ulid', 'employee_id', 'document_type',
        'title', 'file_path', 'mime_type', 'size_bytes',
        'issued_at', 'expires_at', 'metadata', 'uploaded_by',
    ];

    protected $casts = [
        'issued_at' => 'date',
        'expires_at' => 'date',
        'metadata' => 'array',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
