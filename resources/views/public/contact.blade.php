@extends('layouts.public')

@section('title', 'Contato | Makis Digital')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
    <div class="text-center mb-16">
        <h1 class="text-4xl md:text-5xl font-bold font-heading mb-6 text-white">Vamos Conversar</h1>
        <p class="text-xl text-slate-400 max-w-2xl mx-auto">Tem um projeto em mente ou precisa de ajuda com uma solução existente? Estamos prontos para ajudar.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 max-w-5xl mx-auto">
        <!-- Contact Info -->
        <div>
            <h2 class="text-2xl font-bold text-white mb-6">Informações de Contato</h2>
            <div class="space-y-8">
                <div class="flex items-start">
                    <div class="w-12 h-12 rounded-xl bg-indigo-500/10 flex items-center justify-center text-indigo-400 mr-4 shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-white font-medium mb-1">Email</h3>
                        <p class="text-slate-400">contato@makisdigital.com.br</p>
                        <p class="text-slate-500 text-sm mt-1">Respondemos em até 24 horas.</p>
                    </div>
                </div>
                
                <div class="flex items-start">
                    <div class="w-12 h-12 rounded-xl bg-purple-500/10 flex items-center justify-center text-purple-400 mr-4 shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-white font-medium mb-1">Telefone</h3>
                        <p class="text-slate-400">+55 (11) 99999-9999</p>
                        <p class="text-slate-500 text-sm mt-1">Segunda a Sexta, 9h às 18h.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Form -->
        <div class="glass-panel rounded-3xl p-8">
            @if(session('success'))
                <div class="mb-6 p-4 rounded-xl bg-green-500/10 border border-green-500/20 flex items-start">
                    <svg class="w-6 h-6 text-green-400 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-green-300 font-medium">{{ session('success') }}</p>
                </div>
            @endif

            <form action="{{ route('contact.submit') }}" method="POST" class="space-y-6">
                @csrf
                <div style="display: none;">
                    <input type="text" name="website_url" tabindex="-1" autocomplete="off">
                </div>
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-300 mb-2">Nome Completo</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" class="w-full bg-slate-900/50 border border-slate-700/50 rounded-xl p-3 text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" required>
                    @error('name') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-300 mb-2">Email Profissional</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" class="w-full bg-slate-900/50 border border-slate-700/50 rounded-xl p-3 text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" required>
                    @error('email') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="phone" class="block text-sm font-medium text-slate-300 mb-2">Telefone / WhatsApp</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone') }}" class="w-full bg-slate-900/50 border border-slate-700/50 rounded-xl p-3 text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" placeholder="+55 (11) 99999-9999">
                    @error('phone') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="message" class="block text-sm font-medium text-slate-300 mb-2">Como podemos ajudar?</label>
                    <textarea id="message" name="message" rows="4" class="w-full bg-slate-900/50 border border-slate-700/50 rounded-xl p-3 text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors resize-none" required>{{ old('message') }}</textarea>
                    @error('message') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                @if(request('service'))
                    <input type="hidden" name="service_interest" value="{{ request('service') }}">
                    <div class="p-3 bg-indigo-500/10 border border-indigo-500/20 rounded-xl text-sm text-indigo-300">
                        Interesse no serviço: <strong>{{ request('service') }}</strong>
                    </div>
                @endif

                <button type="submit" class="w-full py-3 px-4 bg-indigo-600 hover:bg-indigo-500 text-white font-medium rounded-xl shadow-lg shadow-indigo-500/25 transition-all">
                    Enviar Mensagem
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
