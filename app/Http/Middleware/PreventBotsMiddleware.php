<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class PreventBotsMiddleware
{
    /**
     * Handle an incoming request.
     * Detects if a hidden honeypot field was filled.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Se o campo 'website_url' (que é invisível para humanos) estiver preenchido, é um bot.
        if ($request->filled('website_url')) {
            Log::warning('Bot detection triggered via Honeypot', [
                'ip' => $request->ip(),
                'data' => $request->except(['password', 'password_confirmation']),
            ]);

            // Retornamos um erro genérico para não dar dicas ao bot, ou apenas ignoramos.
            return response()->json(['message' => 'Your request was blocked for security reasons.'], 422);
        }

        return $next($request);
    }
}
