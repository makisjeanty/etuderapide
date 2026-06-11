@extends('layouts.public')

@section('title', 'Contato | ' . config('app.name', 'Etude Rapide'))

@section('content')
<div class="max-w-7xl mx-auto px-6 lg:px-8 py-20">
    <div class="text-center mb-16">
        <h1 class="text-4xl md:text-5xl font-bold font-heading mb-6 text-slate-dark">Vamos Conversar</h1>
        <p class="text-lg md:text-xl text-slate-medium max-w-2xl mx-auto">Tem um projeto em mente ou precisa de ajuda com uma solução existente? Estamos prontos para ajudar.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 max-w-5xl mx-auto">
        <!-- Contact Info -->
        <div class="flex flex-col justify-center">
            <h2 class="text-2xl font-bold text-slate-dark mb-8">Informações de Contato</h2>
            <div class="space-y-8">
                <div class="flex items-start">
                    <div class="w-12 h-12 rounded-xl bg-clay/10 flex items-center justify-center text-clay mr-4 shrink-0 shadow-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-slate-dark font-bold text-base mb-1">Email</h3>
                        <p class="text-slate-medium">contato@makisdigital.com.br</p>
                        <p class="text-slate-light text-xs mt-1">Respondemos em até 24 horas.</p>
                    </div>
                </div>
                
                <div class="flex items-start">
                    <div class="w-12 h-12 rounded-xl bg-clay/10 flex items-center justify-center text-clay mr-4 shrink-0 shadow-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-slate-dark font-bold text-base mb-1">Telefone</h3>
                        <p class="text-slate-medium">+55 (11) 99999-9999</p>
                        <p class="text-slate-light text-xs mt-1">Segunda a Sexta, 9h às 18h.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Form -->
        <div class="bg-white border border-slate-dark/10 rounded-3xl p-8 shadow-sm">
            @if(session('success'))
                <div role="alert" class="mb-6 p-4 rounded-xl bg-green-500/5 border border-green-500/20 flex items-start">
                    <svg class="w-6 h-6 text-green-600 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-green-700 font-semibold text-sm">{{ session('success') }}</p>
                </div>
            @endif

            <p class="text-xs text-slate-light mb-2">Campos marcados com <span aria-hidden="true">*</span><span class="sr-only">asterisco</span> são obrigatórios.</p>

            <form action="{{ route('contact.submit') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label for="name" class="block text-sm font-semibold text-slate-medium mb-2">
                        Nome Completo <span aria-hidden="true" class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}"
                           class="w-full bg-ivory-light border border-slate-dark/20 rounded-2xl p-4 text-slate-dark focus:ring-2 focus:ring-clay focus:border-clay transition-all outline-none"
                           required
                           @error('name') aria-describedby="name-error" aria-invalid="true" @enderror>
                    @error('name') <span id="name-error" role="alert" class="text-red-600 text-sm mt-1.5 block font-medium">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-medium mb-2">
                        Email Profissional <span aria-hidden="true" class="text-red-500">*</span>
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                           class="w-full bg-ivory-light border border-slate-dark/20 rounded-2xl p-4 text-slate-dark focus:ring-2 focus:ring-clay focus:border-clay transition-all outline-none"
                           required
                           @error('email') aria-describedby="email-error" aria-invalid="true" @enderror>
                    @error('email') <span id="email-error" role="alert" class="text-red-600 text-sm mt-1.5 block font-medium">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="phone" class="block text-sm font-semibold text-slate-medium mb-2">Telefone / WhatsApp</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                           class="w-full bg-ivory-light border border-slate-dark/20 rounded-2xl p-4 text-slate-dark focus:ring-2 focus:ring-clay focus:border-clay transition-all outline-none"
                           placeholder="+55 (11) 99999-9999"
                           @error('phone') aria-describedby="phone-error" aria-invalid="true" @enderror>
                    @error('phone') <span id="phone-error" role="alert" class="text-red-600 text-sm mt-1.5 block font-medium">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="message" class="block text-sm font-semibold text-slate-medium mb-2">
                        Como podemos ajudar? <span aria-hidden="true" class="text-red-500">*</span>
                    </label>
                    <textarea id="message" name="message" rows="4"
                              class="w-full bg-ivory-light border border-slate-dark/20 rounded-2xl p-4 text-slate-dark focus:ring-2 focus:ring-clay focus:border-clay transition-all outline-none resize-none"
                              required
                              @error('message') aria-describedby="message-error" aria-invalid="true" @enderror>{{ old('message') }}</textarea>
                    @error('message') <span id="message-error" role="alert" class="text-red-600 text-sm mt-1.5 block font-medium">{{ $message }}</span> @enderror
                </div>
                
                @if(request('service'))
                    <input type="hidden" name="service_interest" value="{{ request('service') }}">
                    <div class="p-4 bg-clay/5 border border-clay/20 rounded-2xl text-sm text-clay font-medium">
                        Interesse no serviço: <strong>{{ request('service') }}</strong>
                    </div>
                @endif

                <div class="hidden" style="display: none;">
                    <input type="text" name="website_url" tabindex="-1" autocomplete="off">
                </div>

                <button type="submit" class="w-full btn-clay py-4 shadow-sm flex items-center justify-center font-bold">
                    Enviar Mensagem
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
