<?php

declare(strict_types=1);

namespace Modules\Request\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReimbursementRequest extends Model
{
    protected $fillable = [
        'workflow_request_id', 'amount', 'currency',
        'category', 'expense_date', 'receipts',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'expense_date' => 'date',
        'receipts' => 'array',
    ];

    public function workflowRequest(): BelongsTo
    {
        return $this->belongsTo(WorkflowRequest::class);
    }
}
