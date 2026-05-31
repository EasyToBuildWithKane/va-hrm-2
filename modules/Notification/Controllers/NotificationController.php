<?php

declare(strict_types=1);

namespace Modules\Notification\Controllers;

use App\Http\Controllers\Controller;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Notification\Models\UserNotification;
use Modules\Notification\Services\NotificationService;

class NotificationController extends Controller
{
    public function __construct(private readonly NotificationService $service)
    {
    }

    public function index(Request $request): JsonResponse
    {
        $items = UserNotification::query()
            ->where('user_id', $request->user()->id)
            ->when($request->boolean('unread_only'), fn ($q) => $q->whereNull('read_at'))
            ->latest()
            ->paginate((int) $request->query('per_page', 15));

        return ApiResponse::success($items->items(), meta: [
            'current_page' => $items->currentPage(),
            'per_page' => $items->perPage(),
            'total' => $items->total(),
            'unread' => UserNotification::query()
                ->where('user_id', $request->user()->id)
                ->whereNull('read_at')
                ->count(),
        ]);
    }

    public function read(UserNotification $notification, Request $request): JsonResponse
    {
        abort_unless($notification->user_id === $request->user()->id, 403);

        return ApiResponse::success($this->service->markRead($notification), 'Marked as read');
    }

    public function readAll(Request $request): JsonResponse
    {
        UserNotification::query()
            ->where('user_id', $request->user()->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return ApiResponse::message('All notifications marked as read');
    }
}
