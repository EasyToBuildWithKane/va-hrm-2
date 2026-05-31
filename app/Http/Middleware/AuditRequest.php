<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuditRequest
{
    public function handle(Request $request, Closure $next): Response
    {
        app()->instance('audit.request.context', [
            'ip' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 500),
            'method' => $request->method(),
            'path' => $request->path(),
        ]);

        return $next($request);
    }
}
