@extends('layouts.public')

@section('title', $service->seo_title ?? ($service->name . ' | ' . config('app.name', 'Etude Rapide')) )
@section('meta_description', $service->seo_description ?? $service->short_description)

@section('content')
<div class="max-w-4xl mx-auto px-6 lg:px-8 py-20">
    <a href="{{ route('services.index') }}" class="inline-flex items-center text-clay hover:text-accent font-semibold mb-10 transition-colors">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Voltar para Serviços
    </a>

    <div class="bg-white border border-slate-dark/10 rounded-3xl p-8 md:p-12 shadow-sm">
        <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
            <h1 class="text-3xl md:text-5xl font-bold font-heading text-slate-dark leading-tight">{{ $service->name }}</h1>
            @if($service->category)
                <span class="px-4 py-1.5 rounded-full bg-clay/5 border border-clay/20 text-clay text-sm font-semibold">
                    {{ $service->category->name }}
                </span>
            @endif
        </div>
        
        <p class="text-lg md:text-xl text-slate-medium mb-10 leading-relaxed border-b border-slate-dark/10 pb-10">
            {{ $service->short_description }}
        </p>
        
        <div class="prose prose-slate max-w-none text-slate-medium leading-relaxed mb-12">
            {!! nl2br(e($service->full_description)) !!}
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 p-6 rounded-2xl bg-ivory-medium border border-slate-dark/10 mb-10">
            @if($service->price_from)
                <div>
                    <span class="block text-sm text-slate-light font-semibold mb-1">Investimento a partir de</span>
                    <span class="text-2xl font-bold text-slate-dark">R$ {{ number_format($service->price_from, 2, ',', '.') }}</span>
                </div>
            @endif
            @if($service->delivery_time)
                <div>
                    <span class="block text-sm text-slate-light font-semibold mb-1">Prazo estimado</span>
                    <span class="text-lg font-bold text-slate-medium">{{ $service->delivery_time }}</span>
                </div>
            @endif
        </div>

        <div class="text-center pt-8 border-t border-slate-dark/10">
            <h3 class="text-xl font-bold text-slate-dark mb-6">{{ $service->call_to_action ?? 'Pronto para iniciar este projeto?' }}</h3>
            <a href="{{ route('contact') }}?service={{ urlencode($service->name) }}" class="inline-flex items-center justify-center btn-clay font-bold shadow-sm">
                {{ $service->call_to_action ?? 'Começar Agora' }}
            </a>
        </div>
    </div>
</div>
@endsection
