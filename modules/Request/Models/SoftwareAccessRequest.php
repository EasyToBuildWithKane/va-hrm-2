<?php

declare(strict_types=1);

namespace Modules\Request\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SoftwareAccessRequest extends Model
{
    protected $fillable = [
        'workflow_request_id', 'software_name', 'access_level', 'needed_by',
    ];

    protected $casts = [
        'needed_by' => 'date',
    ];

    public function workflowRequest(): BelongsTo
    {
        return $this->belongsTo(WorkflowRequest::class);
    }
}
