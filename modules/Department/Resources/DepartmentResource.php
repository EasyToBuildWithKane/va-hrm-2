<?php

declare(strict_types=1);

namespace Modules\Department\Resources;

use App\Http\Resources\BaseResource;

/** @mixin \Modules\Department\Models\Department */
class DepartmentResource extends BaseResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->ulid,
            'internal_id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'parent_id' => $this->parent_id,
            'manager' => $this->whenLoaded('manager', fn () => [
                'id' => $this->manager?->ulid,
                'name' => trim("{$this->manager?->first_name} {$this->manager?->last_name}"),
            ]),
            'headcount_limit' => $this->headcount_limit,
            'is_active' => $this->is_active,
            'metadata' => $this->metadata,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
