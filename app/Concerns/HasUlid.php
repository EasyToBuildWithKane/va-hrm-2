<?php

declare(strict_types=1);

namespace App\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait HasUlid
{
    public static function bootHasUlid(): void
    {
        static::creating(function (Model $model): void {
            if (in_array('ulid', $model->getFillable(), true) && empty($model->ulid)) {
                $model->ulid = (string) Str::ulid();
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'ulid';
    }
}
