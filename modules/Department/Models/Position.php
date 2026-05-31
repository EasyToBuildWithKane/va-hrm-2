<?php

declare(strict_types=1);

namespace Modules\Department\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Position extends BaseModel
{
    use SoftDeletes;

    protected $fillable = [
        'ulid',
        'name',
        'code',
        'department_id',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
}
