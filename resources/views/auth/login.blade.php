<x-guest-layout :title="config('app.name', 'Etuderapide')" subtitle="Área do usuário">
    @if (session('status'))
        <div class="mb-4 font-medium text-sm text-green-400">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="input-group">
            <label for="email" class="sr-only">E-mail</label>
            <input id="email"
                   class="premium-input"
                   type="email"
                   name="email"
                   placeholder="Seu e-mail"
                   value="{{ old('email') }}"
                   required autofocus autocomplete="username"
                   @error('email') aria-describedby="email-error" aria-invalid="true" @enderror />
            <i class="fas fa-envelope" aria-hidden="true"></i>
            @error('email')
                <p id="email-error" class="mt-2 text-red-400 text-xs" role="alert">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div class="input-group mt-4">
            <label for="password" class="sr-only">Senha</label>
            <input id="password"
                   class="premium-input"
                   type="password"
                   name="password"
                   placeholder="Sua senha secreta"
                   required autocomplete="current-password"
                   @error('password') aria-describedby="password-error" aria-invalid="true" @enderror />
            <i class="fas fa-lock" aria-hidden="true"></i>
            @error('password')
                <p id="password-error" class="mt-2 text-red-400 text-xs" role="alert">{{ $message }}</p>
            @enderror
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between mt-6">
            <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                <input id="remember_me" type="checkbox" class="rounded border-slate-700 bg-slate-800 text-amber-500 shadow-sm focus:ring-amber-500 focus:ring-offset-slate-900" name="remember">
                <span class="ms-2 text-sm text-slate-400 group-hover:text-slate-200 transition-colors">Lembrar de mim</span>
            </label>

            @if (Route::has('password.request'))
                <a class="footer-link" href="{{ route('password.request') }}">
                    Esqueceu a senha?
                </a>
            @endif
        </div>

        <button type="submit" class="login-btn">
            Entrar <i class="fas fa-arrow-right ml-2" aria-hidden="true"></i>
        </button>

        <div class="mt-6 text-center">
            <a class="footer-link" href="{{ route('admin.login') }}">
                Acesso administrativo
            </a>
        </div>
    </form>
</x-guest-layout>
