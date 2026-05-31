<?php

declare(strict_types=1);

namespace Modules\Employee\Controllers;

use App\Http\Controllers\Controller;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Modules\Employee\Models\Employee;

class EmployeeTimelineController extends Controller
{
    public function show(Employee $employee): JsonResponse
    {
        return ApiResponse::success($employee->timeline()->limit(200)->get());
    }
}
