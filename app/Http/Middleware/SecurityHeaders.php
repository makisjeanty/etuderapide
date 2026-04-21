<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $response = $next($request);
        } catch (\Exception $e) {
            // Em caso de exceção, deixamos o Laravel processar mas não bloqueamos o fluxo
            throw $e;
        }

        if ($response instanceof Response) {
            if ($request->isSecure() || app()->isProduction()) {
                $response->header('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
            }

            $response->header('X-Content-Type-Options', 'nosniff');
            $response->header('X-Frame-Options', 'SAMEORIGIN');
            $response->header('X-XSS-Protection', '1; mode=block');
            $response->header('Referrer-Policy', 'strict-origin-when-cross-origin');
            $response->header('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');

            // Content Security Policy (Blindagem de Injeção - Nível Produção)
            $csp = "default-src 'self'; ";
            $csp .= "script-src 'self' https://cdn.jsdelivr.net https://fonts.bunny.net; ";
            $csp .= "style-src 'self' https://fonts.bunny.net; ";
            $csp .= "font-src 'self' data: https://fonts.bunny.net; ";
            $csp .= "img-src 'self' data: https:; "; // Imagens permitimos de qualquer HTTPS (para banners/mídias)
            $csp .= "connect-src 'self' https:; ";
            $csp .= "frame-src 'self' https:; ";
            // $csp .= "upgrade-insecure-requests; "; // Removido para evitar bloqueios no localhost

            $response->header('Content-Security-Policy', $csp);
        }

        return $response;
    }
}
