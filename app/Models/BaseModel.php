<?php

declare(strict_types=1);

namespace App\Models;

use App\Concerns\HasActor;
use App\Concerns\HasUlid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model
{
    use HasActor;
    use HasFactory;
    use HasUlid;
}
