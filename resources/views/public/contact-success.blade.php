@extends('layouts.public')

@section('title', 'Mensagem Recebida | ' . config('app.name', 'Etude Rapide'))

@section('content')
<div class="min-h-[70vh] flex items-center justify-center py-20 px-6">
    <div class="max-w-2xl w-full text-center">
        <!-- Success Icon -->
        <div class="mb-10 inline-flex items-center justify-center w-24 h-24 rounded-full bg-clay/10 text-clay border border-clay/20 shadow-sm relative overflow-hidden group">
            <div class="absolute inset-0 bg-clay/5 scale-0 group-hover:scale-100 transition-transform duration-500 rounded-full"></div>
            <svg class="w-12 h-12 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>

        <h1 class="text-4xl md:text-5xl font-bold font-heading text-slate-dark mb-6 tracking-tight">
            Mensagem enviada com <span class="text-clay">sucesso!</span>
        </h1>
        
        <p class="text-lg md:text-xl text-slate-medium mb-12 leading-relaxed">
            Obrigado pelo seu interesse. Acabamos de te enviar um e-mail de confirmação. 
            Nosso time técnico já está analisando sua solicitação e entraremos em contato em até 24 horas úteis.
        </p>

        <div class="bg-oat border border-slate-dark/10 p-8 rounded-3xl mb-12 shadow-sm">
            <h3 class="text-slate-dark font-bold mb-6 text-lg">O que você pode fazer agora?</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <a href="{{ route('projects.index') }}" class="p-5 rounded-2xl bg-white border border-slate-dark/10 hover:border-clay hover:bg-ivory-light transition-all duration-300 text-left group shadow-xs">
                    <div class="text-clay group-hover:text-accent font-bold mb-1.5 flex items-center">
                        Ver Cases <span class="ml-1 group-hover:translate-x-1 transition-transform">&rarr;</span>
                    </div>
                    <div class="text-xs text-slate-light font-semibold">Inspire-se com projetos que já entregamos.</div>
                </a>
                <a href="{{ route('blog.index') }}" class="p-5 rounded-2xl bg-white border border-slate-dark/10 hover:border-clay hover:bg-ivory-light transition-all duration-300 text-left group shadow-xs">
                    <div class="text-clay group-hover:text-accent font-bold mb-1.5 flex items-center">
                        Ler Blog <span class="ml-1 group-hover:translate-x-1 transition-transform">&rarr;</span>
                    </div>
                    <div class="text-xs text-slate-light font-semibold">Dicas de IA e tecnologia para o seu negócio.</div>
                </a>
            </div>
        </div>

        <a href="{{ route('home') }}" class="inline-flex items-center text-slate-medium hover:text-slate-dark font-semibold transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Voltar para a página inicial
        </a>
    </div>
</div>
@endsection
