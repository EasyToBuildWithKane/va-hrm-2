<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Exceptions\PermissionException;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $user = $request->user();

        if ($user === null) {
            throw new PermissionException('Unauthenticated', $permission);
        }

        if ($user->hasPermissionTo($permission) || $user->hasActiveDelegationFor($permission)) {
            return $next($request);
        }

        throw new PermissionException("Access denied: {$permission}", $permission);
    }
}
