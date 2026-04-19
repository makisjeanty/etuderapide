<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TwoFactorVerification
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Se não estiver logado, ou não tiver acesso ao painel, segue o fluxo normal.
        if (! $user || ! $user->canAccessAdminPanel()) {
            return $next($request);
        }

        // Se a sessão já estiver verificada, segue o fluxo
        if (session('2fa_verified')) {
            return $next($request);
        }

        // Se a rota atual já for o desafio, evita loop
        if ($request->routeIs('admin.2fa.*')) {
            return $next($request);
        }

        // Redireciona para o desafio de 2FA
        return redirect()->route('admin.2fa.index');
    }
}
