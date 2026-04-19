<?php

use App\Http\Middleware\EnsureUserIsAdmin;
use App\Http\Middleware\PreventBotsMiddleware;
use App\Http\Middleware\SecurityHeaders;
use App\Http\Middleware\TwoFactorVerification;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

$trustedProxies = env('TRUSTED_PROXIES');

if (is_string($trustedProxies) && $trustedProxies !== '' && $trustedProxies !== '*') {
    $trustedProxies = array_values(array_filter(array_map('trim', explode(',', $trustedProxies))));
}

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function (): void {
            Route::middleware(['web', 'auth', 'verified', 'admin', 'two_factor'])
                ->prefix(config('app.admin_prefix', 'gestao-makis'))
                ->name('admin.')
                ->group(base_path('routes/admin.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) use ($trustedProxies): void {
        // Trust proxies only when they are explicitly configured via TRUSTED_PROXIES.
        $middleware->trustProxies(at: $trustedProxies ?: null);

        // Adiciona cabeçalhos de segurança a todas as rotas
        $middleware->append(SecurityHeaders::class);

        $middleware->alias([
            'admin' => EnsureUserIsAdmin::class,
            'bot_protection' => PreventBotsMiddleware::class,
            'two_factor' => TwoFactorVerification::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->report(function (QueryException $e) {
            Log::critical('FALHA CRÍTICA DE BANCO DE DADOS: '.$e->getMessage());
        });
    })->create();
