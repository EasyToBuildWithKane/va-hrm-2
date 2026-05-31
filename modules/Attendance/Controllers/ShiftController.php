<?php

declare(strict_types=1);

namespace Modules\Attendance\Controllers;

use App\Http\Controllers\Controller;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Attendance\Models\AttendanceShift;
use Modules\Attendance\Services\ShiftService;

class ShiftController extends Controller
{
    public function __construct(private readonly ShiftService $service)
    {
    }

    public function index(): JsonResponse
    {
        return ApiResponse::success(AttendanceShift::query()->get());
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string'],
            'code' => ['required', 'string', 'unique:attendance_shifts,code'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i'],
            'grace_minutes' => ['integer', 'min:0', 'max:120'],
            'break_minutes' => ['integer', 'min:0'],
            'working_days' => ['array'],
        ]);

        return ApiResponse::success($this->service->create($data), 'Shift created', status: 201);
    }

    public function update(Request $request, AttendanceShift $shift): JsonResponse
    {
        $shift->update($request->validate([
            'name' => ['sometimes', 'string'],
            'start_time' => ['sometimes', 'date_format:H:i'],
            'end_time' => ['sometimes', 'date_format:H:i'],
            'grace_minutes' => ['sometimes', 'integer'],
            'break_minutes' => ['sometimes', 'integer'],
            'working_days' => ['sometimes', 'array'],
            'is_active' => ['sometimes', 'boolean'],
        ]));

        return ApiResponse::success($shift, 'Shift updated');
    }

    public function destroy(AttendanceShift $shift): JsonResponse
    {
        $shift->delete();

        return ApiResponse::message('Shift deleted');
    }

    public function assign(Request $request, AttendanceShift $shift): JsonResponse
    {
        $data = $request->validate([
            'employee_ids' => ['required', 'array'],
            'employee_ids.*' => ['exists:employees,id'],
            'valid_from' => ['required', 'date'],
            'valid_until' => ['nullable', 'date', 'after:valid_from'],
        ]);

        $this->service->assign($shift, $data['employee_ids'], $data['valid_from'], $data['valid_until'] ?? null);

        return ApiResponse::message('Shift assigned');
    }
}
