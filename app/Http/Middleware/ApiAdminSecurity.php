<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiAdminSecurity
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        if (! $user->canAccessAdminPanel()) {
            return response()->json(['error' => 'Forbidden: Administrator or Management access required'], 403);
        }

        if (! $user->hasVerifiedEmail()) {
            return response()->json(['error' => 'Forbidden: Email verification required'], 403);
        }

        if (! $user->tokenCan('2fa:verified')) {
            return response()->json(['error' => 'Forbidden: Two-factor authentication required for this token'], 403);
        }

        $ability = $this->resolveAbility($request);
        if ($ability && ! $user->tokenCan($ability)) {
            return response()->json(['error' => 'Forbidden: Insufficient token permissions'], 403);
        }

        return $next($request);
    }

    private function resolveAbility(Request $request): ?string
    {
        if ($request->routeIs('api.admin.summary', 'api.v1.admin.summary')) {
            return 'dashboard:read';
        }

        if ($request->routeIs('api.admin.leads.*', 'api.v1.admin.leads.*')) {
            return 'leads:manage';
        }

        if ($request->routeIs('api.admin.posts.*', 'api.v1.admin.posts.*')) {
            return 'posts:manage';
        }

        if ($request->routeIs('api.admin.projects.*', 'api.v1.admin.projects.*')) {
            return 'projects:manage';
        }

        if ($request->routeIs('api.admin.services.*', 'api.v1.admin.services.*')) {
            return 'services:manage';
        }

        return null;
    }
}
