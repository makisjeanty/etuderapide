<?php

namespace App\Providers;

use App\Services\AuditLogger;
use Illuminate\Auth\Events\Login;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Força HTTPS em produção (evitando problemas se a infra não estiver pronta localmente)
        if ($this->app->isProduction()) {
            URL::forceScheme('https');
        }

        // Modo estrito do Eloquent apenas em ambiente local e de testes (pega N+1 e outros erros de cara)
        Model::shouldBeStrict(! $this->app->isProduction());

        Event::listen(Login::class, function (Login $event): void {
            AuditLogger::record($event->user, 'auth.login', null, null, [
                'guard' => $event->guard,
            ]);
        });

        // Configuração de Rate Limiters (Hardening)
        $this->configureRateLimiting();
    }

    /**
     * Configure the rate limiters for the application.
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('global', function (Request $request) {
            return Limit::perMinute(100)->by($request->ip());
        });

        RateLimiter::for('ai_analysis', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip())->response(function () {
                return response()->json(['error' => 'Muitas solicitações de IA. Tente novamente em 1 minuto.'], 429);
            });
        });

        RateLimiter::for('admin_login', function (Request $request) {
            return Limit::perMinute(10)->by($request->ip());
        });

        RateLimiter::for('api_login', function (Request $request) {
            $email = (string) $request->input('email', 'guest');

            return Limit::perMinute(5)->by(strtolower($email).'|'.$request->ip());
        });

        RateLimiter::for('admin_2fa', function (Request $request) {
            $key = implode('|', [
                '2fa',
                optional($request->user())->getAuthIdentifier() ?? 'guest',
                $request->ip(),
            ]);

            return Limit::perMinutes(10, 5)->by($key);
        });

        RateLimiter::for('admin_2fa_resend', function (Request $request) {
            $key = implode('|', [
                '2fa_resend',
                optional($request->user())->getAuthIdentifier() ?? 'guest',
                $request->ip(),
            ]);

            return Limit::perMinutes(10, 3)->by($key);
        });
    }
}
