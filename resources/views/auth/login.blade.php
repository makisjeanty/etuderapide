<x-guest-layout>
    @if (session('status'))
        <div class="mb-4 font-medium text-sm text-green-400">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="input-group">
            <input id="email" 
                   class="premium-input" 
                   type="email" 
                   name="email" 
                   placeholder="Seu e-mail"
                   value="{{ old('email') }}" 
                   required autofocus autocomplete="username" />
            <i class="fas fa-envelope"></i>
            @error('email')
                <p class="mt-2 text-red-400 text-xs">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div class="input-group mt-4">
            <input id="password" 
                   class="premium-input"
                   type="password"
                   name="password"
                   placeholder="Sua senha secreta"
                   required autocomplete="current-password" />
            <i class="fas fa-lock"></i>
            @error('password')
                <p class="mt-2 text-red-400 text-xs">{{ $message }}</p>
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
            Acessar Sistema <i class="fas fa-arrow-right ml-2"></i>
        </button>
    </form>
</x-guest-layout>
