@extends('layouts.public')

@section('title', 'Mensagem Recebida | Makis Digital')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center py-20 px-4">
    <div class="max-w-2xl w-full text-center">
        <!-- Ícone de Sucesso -->
        <div class="mb-10 inline-flex items-center justify-center w-24 h-24 rounded-full bg-indigo-500/20 text-indigo-400 border border-indigo-500/30 shadow-2xl shadow-indigo-500/20 relative overflow-hidden group">
            <div class="absolute inset-0 bg-indigo-500/10 scale-0 group-hover:scale-100 transition-transform duration-500 rounded-full"></div>
            <svg class="w-12 h-12 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>

        <h1 class="text-4xl md:text-5xl font-bold font-heading text-white mb-6 tracking-tight">
            Mensagem enviada com <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-purple-400">sucesso!</span>
        </h1>
        
        <p class="text-xl text-slate-400 mb-12 leading-relaxed">
            Obrigado pelo seu interesse. Acabamos de te enviar um e-mail de confirmação. 
            Nosso time técnico já está analisando sua solicitação e entraremos em contato em até 24 horas úteis.
        </p>

        <div class="glass-panel p-8 rounded-3xl border-indigo-500/20 bg-gradient-to-br from-indigo-600/5 to-purple-600/5 mb-12">
            <h3 class="text-white font-bold mb-4">O que você pode fazer agora?</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <a href="{{ route('projects.index') }}" class="p-4 rounded-2xl bg-slate-900/50 border border-slate-800 hover:border-indigo-500/50 hover:bg-slate-800 transition-all text-left group">
                    <div class="text-indigo-400 font-bold mb-1 group-hover:translate-x-1 transition-transform">Ver Cases &rarr;</div>
                    <div class="text-xs text-slate-500">Inspire-se com projetos que já entregamos.</div>
                </a>
                <a href="{{ route('blog.index') }}" class="p-4 rounded-2xl bg-slate-900/50 border border-slate-800 hover:border-indigo-500/50 hover:bg-slate-800 transition-all text-left group">
                    <div class="text-indigo-400 font-bold mb-1 group-hover:translate-x-1 transition-transform">Ler Blog &rarr;</div>
                    <div class="text-xs text-slate-500">Dicas de IA e tecnologia para o seu negócio.</div>
                </a>
            </div>
        </div>

        <a href="{{ route('home') }}" class="inline-flex items-center text-slate-400 hover:text-white transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Voltar para a página inicial
        </a>
    </div>
</div>
@endsection
