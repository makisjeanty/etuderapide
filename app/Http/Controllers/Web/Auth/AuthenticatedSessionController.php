<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Display the admin login view.
     */
    public function createAdmin(): View
    {
        return view('auth.admin-login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $intended = $request->session()->get('url.intended');

        $request->session()->regenerate();

        if ($this->canRedirectToIntended($request, $intended)) {
            return redirect()->to($intended);
        }

        $user = $request->user();

        if ($user && $user->canAccessAdminPanel()) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('profile.edit');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    protected function canRedirectToIntended(LoginRequest $request, ?string $intended): bool
    {
        if (! is_string($intended) || $intended === '') {
            return false;
        }

        $path = parse_url($intended, PHP_URL_PATH);

        if (! is_string($path) || $path === '') {
            return false;
        }

        $normalizedPath = '/'.ltrim($path, '/');
        $adminPath = '/'.trim((string) config('app.admin_prefix', 'gestao-makis'), '/');

        if (Str::startsWith($normalizedPath, $adminPath)) {
            return (bool) $request->user()?->canAccessAdminPanel();
        }

        return true;
    }
}
