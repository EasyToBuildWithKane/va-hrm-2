<?php

declare(strict_types=1);

namespace App\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

trait HasActor
{
    public static function bootHasActor(): void
    {
        static::creating(function (Model $model): void {
            if (in_array('created_by', $model->getFillable(), true) && empty($model->created_by)) {
                $model->created_by = Auth::id();
            }
            if (in_array('updated_by', $model->getFillable(), true) && empty($model->updated_by)) {
                $model->updated_by = Auth::id();
            }
        });

        static::updating(function (Model $model): void {
            if (in_array('updated_by', $model->getFillable(), true)) {
                $model->updated_by = Auth::id();
            }
        });
    }
}
