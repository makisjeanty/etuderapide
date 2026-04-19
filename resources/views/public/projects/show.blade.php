@extends('layouts.public')

@section('title', $project->seo_title ?? $project->title . ' | Makis Digital')
@section('meta_description', $project->seo_description ?? $project->summary)

@section('content')
<!-- Project Hero -->
<div class="relative pt-20 pb-32 border-b border-slate-800/60 overflow-hidden">
    <div class="absolute inset-0 z-0">
        @if($project->featured_image)
            <img src="{{ $project->featured_image }}" alt="" class="w-full h-full object-cover opacity-10">
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/80 to-slate-950/40"></div>
        @endif
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <a href="{{ route('projects.index') }}" class="inline-flex items-center text-indigo-400 hover:text-indigo-300 font-medium mb-12 transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Voltar para Portfólio
        </a>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 items-end">
            <div class="lg:col-span-2">
                @if($project->category)
                    <div class="mb-4">
                        <span class="px-3 py-1 bg-indigo-500/20 text-indigo-400 rounded-lg border border-indigo-500/30 uppercase tracking-widest text-[10px] font-bold">
                            {{ $project->category->name }}
                        </span>
                    </div>
                @endif
                <h1 class="text-4xl md:text-6xl font-bold font-heading text-white mb-6 leading-tight">{{ $project->title }}</h1>
                <p class="text-xl text-slate-300 leading-relaxed">{{ $project->summary }}</p>
            </div>
            <div class="glass-panel p-6 rounded-2xl flex flex-col gap-4">
                @if($project->started_at || $project->finished_at)
                    <div class="text-sm">
                        <span class="block text-slate-500 mb-1">Período</span>
                        <span class="text-white font-medium">
                            {{ $project->started_at ? $project->started_at->format('M Y') : 'N/A' }} 
                            &rarr; 
                            {{ $project->finished_at ? $project->finished_at->format('M Y') : 'Presente' }}
                        </span>
                    </div>
                @endif
                
                @if($project->demo_url || $project->repository_url)
                    <div class="h-px bg-slate-800 my-2"></div>
                    <div class="flex flex-col gap-3">
                        @if($project->demo_url)
                            <a href="{{ $project->demo_url }}" target="_blank" rel="noopener noreferrer" class="flex items-center text-indigo-400 hover:text-indigo-300 font-medium transition-colors">
                                Ver Projeto ao Vivo
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                            </a>
                        @endif
                        @if($project->repository_url)
                            <a href="{{ $project->repository_url }}" target="_blank" rel="noopener noreferrer" class="flex items-center text-slate-400 hover:text-white font-medium transition-colors">
                                Código Fonte (GitHub)
                                <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd"/></svg>
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Project Content -->
<div class="py-20 bg-slate-950">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-12">
            
            <div class="lg:col-span-3">
                @if($project->featured_image)
                    <div class="rounded-3xl overflow-hidden mb-12 shadow-2xl border border-slate-800">
                        <img src="{{ $project->featured_image }}" alt="Preview" class="w-full h-auto">
                    </div>
                @endif
                
                <div class="prose prose-invert prose-indigo prose-lg max-w-none">
                    {!! nl2br(e($project->description)) !!}
                </div>
            </div>

            <!-- Sidebar (Tech Stack) -->
            <div class="lg:col-span-1">
                @if($project->tech_stack && count($project->tech_stack) > 0)
                    <div class="sticky top-28 glass-panel rounded-2xl p-6">
                        <h3 class="text-lg font-bold text-white mb-4">Tecnologias Utilizadas</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($project->tech_stack as $tech)
                                <span class="px-3 py-1.5 text-sm font-medium rounded-lg bg-slate-800/80 border border-slate-700 text-slate-300 flex items-center">
                                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 mr-2"></span>
                                    {{ $tech }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>

<!-- CTA -->
<div class="py-20 border-t border-slate-800/50 bg-gradient-to-b from-slate-950 to-indigo-950/20">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <h2 class="text-3xl font-bold font-heading text-white mb-6">Gostou deste projeto?</h2>
        <p class="text-xl text-slate-400 mb-8">Nós podemos construir algo incrível para você também.</p>
        <a href="{{ route('contact') }}" class="inline-flex items-center justify-center px-8 py-4 text-base font-medium rounded-xl text-white bg-indigo-600 hover:bg-indigo-500 shadow-[0_0_20px_rgba(79,70,229,0.4)] transition-all">
            Iniciar Conversa
        </a>
    </div>
</div>
@endsection
