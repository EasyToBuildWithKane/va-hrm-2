<?php

declare(strict_types=1);

namespace Modules\Request\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccountRequest extends Model
{
    protected $fillable = [
        'workflow_request_id', 'account_type', 'access_scopes',
    ];

    protected $casts = [
        'access_scopes' => 'array',
    ];

    public function workflowRequest(): BelongsTo
    {
        return $this->belongsTo(WorkflowRequest::class);
    }
}
