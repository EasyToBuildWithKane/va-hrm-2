<?php

declare(strict_types=1);

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->renderable(function (Throwable $e, Request $request) {
            if ($request->is('api/*') || $request->wantsJson()) {
                return $this->renderApi($e);
            }

            return null;
        });
    }

    protected function renderApi(Throwable $e): JsonResponse
    {
        return match (true) {
            $e instanceof ValidationException => $this->respond(
                Response::HTTP_UNPROCESSABLE_ENTITY,
                'Validation failed',
                'VALIDATION_ERROR',
                ['errors' => $e->errors()]
            ),
            $e instanceof AuthenticationException => $this->respond(
                Response::HTTP_UNAUTHORIZED,
                'Unauthenticated',
                'UNAUTHENTICATED'
            ),
            $e instanceof AuthorizationException => $this->respond(
                Response::HTTP_FORBIDDEN,
                $e->getMessage() ?: 'Permission denied',
                'PERMISSION_DENIED'
            ),
            $e instanceof PermissionException => $this->respond(
                Response::HTTP_FORBIDDEN,
                $e->getMessage(),
                'PERMISSION_DENIED',
                ['required' => $e->requiredPermission]
            ),
            $e instanceof WorkflowException => $this->respond(
                $e->httpStatus,
                $e->getMessage(),
                $e->errorCode,
                $e->context
            ),
            $e instanceof ProvisioningException => $this->respond(
                Response::HTTP_UNPROCESSABLE_ENTITY,
                $e->getMessage(),
                'PROVISIONING_ERROR',
                $e->context
            ),
            $e instanceof ModelNotFoundException,
            $e instanceof NotFoundHttpException => $this->respond(
                Response::HTTP_NOT_FOUND,
                'Resource not found',
                'NOT_FOUND'
            ),
            $e instanceof HttpExceptionInterface => $this->respond(
                $e->getStatusCode(),
                $e->getMessage() ?: 'Request failed',
                'HTTP_ERROR'
            ),
            default => $this->respond(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                config('app.debug') ? $e->getMessage() : 'Internal server error',
                'SERVER_ERROR'
            ),
        };
    }

    /**
     * @param  array<string, mixed>  $extra
     */
    protected function respond(int $status, string $message, string $code, array $extra = []): JsonResponse
    {
        return response()->json(array_merge([
            'success' => false,
            'message' => $message,
            'code' => $code,
        ], $extra), $status);
    }
}
