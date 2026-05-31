<?php

declare(strict_types=1);

namespace Modules\Attendance\Controllers;

use App\Http\Controllers\Controller;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Attendance\Actions\CheckInAction;
use Modules\Attendance\Actions\CheckOutAction;
use Modules\Attendance\Actions\CorrectAttendanceAction;
use Modules\Attendance\Models\Attendance;
use Modules\Attendance\Repositories\Contracts\AttendanceRepositoryInterface;
use Modules\Attendance\Services\AttendanceService;

class AttendanceController extends Controller
{
    public function __construct(
        private readonly AttendanceRepositoryInterface $repository,
        private readonly AttendanceService $service,
        private readonly CheckInAction $checkInAction,
        private readonly CheckOutAction $checkOutAction,
        private readonly CorrectAttendanceAction $correctAction,
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $paginated = $this->repository->paginate(
            $request->only(['employee_id', 'status', 'from', 'to']),
            (int) $request->query('per_page', 15),
        );

        return ApiResponse::success($paginated->items(), meta: [
            'total' => $paginated->total(),
            'per_page' => $paginated->perPage(),
            'current_page' => $paginated->currentPage(),
        ]);
    }

    public function show(Attendance $attendance): JsonResponse
    {
        return ApiResponse::success($attendance->load('employee'));
    }

    public function checkIn(Request $request): JsonResponse
    {
        $employee = $request->user()->employee;
        abort_unless($employee !== null, 422, 'No employee profile linked to your account');

        return ApiResponse::success(
            ($this->checkInAction)($employee, $request->ip() ?? '0.0.0.0'),
            'Checked in',
        );
    }

    public function checkOut(Request $request): JsonResponse
    {
        $employee = $request->user()->employee;
        abort_unless($employee !== null, 422, 'No employee profile linked to your account');

        return ApiResponse::success(
            ($this->checkOutAction)($employee, $request->ip() ?? '0.0.0.0'),
            'Checked out',
        );
    }

    public function correction(Request $request): JsonResponse
    {
        $data = $request->validate([
            'attendance_record_id' => ['required', 'exists:attendance_records,id'],
            'proposed_values' => ['required', 'array'],
            'reason' => ['required', 'string'],
        ]);

        $attendance = Attendance::findOrFail($data['attendance_record_id']);
        $correction = ($this->correctAction)($attendance, $data['proposed_values'], $data['reason']);

        return ApiResponse::success($correction, 'Correction submitted', status: 201);
    }

    public function analytics(Request $request): JsonResponse
    {
        return ApiResponse::success(
            $this->service->analytics($request->integer('employee_id') ?: null)
        );
    }
}
