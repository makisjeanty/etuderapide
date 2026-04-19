@extends('layouts.public')

@section('title', $service->seo_title ?? $service->name . ' | Makis Digital')
@section('meta_description', $service->seo_description ?? $service->short_description)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
    <a href="{{ route('services.index') }}" class="inline-flex items-center text-indigo-400 hover:text-indigo-300 font-medium mb-10 transition-colors">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Voltar para Serviços
    </a>

    <div class="glass-panel rounded-3xl p-8 md:p-12">
        <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
            <h1 class="text-4xl md:text-5xl font-bold font-heading text-white">{{ $service->name }}</h1>
            @if($service->category)
                <span class="px-4 py-1.5 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-300 text-sm font-medium">
                    {{ $service->category->name }}
                </span>
            @endif
        </div>
        
        <p class="text-xl text-slate-300 mb-10 leading-relaxed border-b border-slate-800 pb-10">
            {{ $service->short_description }}
        </p>
        
        <div class="prose prose-invert prose-indigo max-w-none mb-12">
            {!! nl2br(e($service->full_description)) !!}
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 p-6 rounded-2xl bg-slate-900/50 border border-slate-800 mb-10">
            @if($service->price_from)
                <div>
                    <span class="block text-sm text-slate-500 mb-1">Investimento a partir de</span>
                    <span class="text-2xl font-bold text-white">R$ {{ number_format($service->price_from, 2, ',', '.') }}</span>
                </div>
            @endif
            @if($service->delivery_time)
                <div>
                    <span class="block text-sm text-slate-500 mb-1">Prazo estimado</span>
                    <span class="text-lg font-medium text-slate-300">{{ $service->delivery_time }}</span>
                </div>
            @endif
        </div>

        <div class="text-center pt-8 border-t border-slate-800/50">
            <h3 class="text-xl font-bold text-white mb-6">{{ $service->call_to_action ?? 'Pronto para iniciar este projeto?' }}</h3>
            <a href="{{ route('contact') }}?service={{ urlencode($service->name) }}" class="inline-flex items-center justify-center px-8 py-4 text-base font-medium rounded-xl text-white bg-indigo-600 hover:bg-indigo-500 shadow-lg shadow-indigo-500/30 transition-all">
                {{ $service->call_to_action ?? 'Começar Agora' }}
            </a>
        </div>
    </div>
</div>
@endsection
