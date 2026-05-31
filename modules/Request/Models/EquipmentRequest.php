<?php

declare(strict_types=1);

namespace Modules\Request\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EquipmentRequest extends Model
{
    protected $fillable = [
        'workflow_request_id', 'equipment_type', 'model', 'quantity', 'estimated_cost',
    ];

    protected $casts = [
        'estimated_cost' => 'decimal:2',
    ];

    public function workflowRequest(): BelongsTo
    {
        return $this->belongsTo(WorkflowRequest::class);
    }
}
