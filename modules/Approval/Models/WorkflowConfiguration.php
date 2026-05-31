<?php

declare(strict_types=1);

namespace Modules\Approval\Models;

use App\Exceptions\WorkflowException;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $workflow_type
 * @property array $config
 * @property bool $is_active
 */
class WorkflowConfiguration extends Model
{
    protected $fillable = [
        'workflow_type', 'config', 'is_active', 'created_by', 'updated_by',
    ];

    protected $casts = [
        'config' => 'array',
        'is_active' => 'boolean',
    ];

    public static function getActiveConfig(string $workflowType): self
    {
        $config = self::query()
            ->where('workflow_type', $workflowType)
            ->where('is_active', true)
            ->first();

        if (! $config) {
            throw WorkflowException::configurationMissing($workflowType);
        }

        return $config;
    }
}
