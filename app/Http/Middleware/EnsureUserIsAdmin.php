<?php

namespace App\Http\Middleware;

use App\Services\AuditLogger;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! $user->canAccessAdminPanel()) {
            if ($user) {
                AuditLogger::record($user, 'admin.access_denied', null, null, [
                    'path' => $request->path(),
                ], $request);
            }

            abort(Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
