<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Este é um acesso administrativo seguro. Por favor, insira o código de 6 dígitos enviado para seu e-mail para continuar.') }}
    </div>

    @if (session('success'))
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.2fa.store') }}">
        @csrf

        <div>
            <x-input-label for="code" :value="__('Código de Acesso')" />
            <x-text-input id="code" class="block mt-1 w-full text-center text-2xl tracking-widest font-bold" type="text" name="code" required autofocus autocomplete="one-time-code" maxlength="6" />
            <x-input-error :messages="$errors->get('code')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-6">
            <button type="submit" class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 transition ease-in-out duration-150 shadow-lg shadow-indigo-500/20">
                {{ __('Verificar Código') }}
            </button>
        </div>
    </form>

    <div class="mt-6 border-t pt-6 text-center">
        <form method="POST" action="{{ route('admin.2fa.resend') }}">
            @csrf
            <button type="submit" class="text-sm text-indigo-600 hover:text-indigo-900 font-medium underline">
                {{ __('Não recebeu o código? Clique para reenviar.') }}
            </button>
        </form>
    </div>
</x-guest-layout>
