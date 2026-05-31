<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Http\JsonResponse;

class ApiResponse
{
    /**
     * @param  array<string, mixed>|null  $meta
     */
    public static function success(mixed $data = null, ?string $message = null, ?array $meta = null, int $status = 200): JsonResponse
    {
        $payload = ['success' => true];

        if ($data !== null) {
            $payload['data'] = $data;
        }
        if ($message !== null) {
            $payload['message'] = $message;
        }
        if ($meta !== null) {
            $payload['meta'] = $meta;
        }

        return response()->json($payload, $status);
    }

    public static function message(string $message, int $status = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
        ], $status);
    }

    /**
     * @param  array<string, mixed>  $extra
     */
    public static function error(string $message, string $code = 'ERROR', int $status = 400, array $extra = []): JsonResponse
    {
        return response()->json(array_merge([
            'success' => false,
            'message' => $message,
            'code' => $code,
        ], $extra), $status);
    }
}
