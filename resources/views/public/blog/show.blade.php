@extends('layouts.public')

@section('title', $post->seo_title ?? ($post->title . ' | Makis Digital'))
@section('meta_description', $post->seo_description ?? Str::limit(strip_tags($post->body), 160))

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
    <a href="{{ route('blog.index') }}" class="inline-flex items-center text-indigo-400 hover:text-indigo-300 font-medium mb-10 transition-colors">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Voltar para o Blog
    </a>

    <article class="glass-panel rounded-[2.5rem] overflow-hidden">
        @if($post->featured_image)
            <div class="aspect-video w-full overflow-hidden border-b border-slate-800">
                <img src="{{ $post->featured_image }}" alt="{{ $post->title }}" class="w-full h-full object-cover">
            </div>
        @endif

        <div class="p-8 md:p-16">
            <header class="mb-12 border-b border-slate-800 pb-10">
                <div class="flex flex-wrap items-center gap-4 text-sm font-medium text-slate-400 mb-8">
                    @if($post->category)
                        <span class="px-3 py-1 bg-indigo-500/10 text-indigo-400 rounded-lg border border-indigo-500/20 uppercase tracking-widest text-[10px] font-bold">
                            {{ $post->category->name }}
                        </span>
                    @endif
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-2 text-indigo-400/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        {{ $post->published_at ? $post->published_at->translatedFormat('d de F, Y') : 'Em Breve' }}
                    </span>
                    <span>&bull;</span>
                    <span class="flex items-center">
                        @if($post->author)
                            <div class="w-5 h-5 rounded-full bg-slate-800 flex items-center justify-center text-[10px] mr-2 border border-slate-700 text-white font-bold">
                                {{ substr($post->author->name, 0, 1) }}
                            </div>
                            {{ $post->author->name }}
                        @else
                            Equipe
                        @endif
                    </span>
                </div>
                
                <h1 class="text-4xl md:text-6xl font-bold font-heading text-white leading-[1.1] tracking-tight">
                    {{ $post->title }}
                </h1>
            </header>
        
        <div class="prose prose-invert prose-indigo prose-lg max-w-none">
            {!! $post->body !!}
        </div>
        
        <div class="mt-16 p-8 md:p-12 rounded-[2rem] bg-gradient-to-br from-indigo-600 to-purple-700 text-white relative overflow-hidden shadow-2xl shadow-indigo-500/20">
            <div class="absolute top-0 right-0 -mt-8 -mr-8 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
            <div class="relative z-10">
                <h3 class="text-2xl md:text-3xl font-bold font-heading mb-4 text-center md:text-left">Precisa de ajuda para implementar isso no seu negócio?</h3>
                <p class="text-indigo-100 mb-8 text-lg">Nós ajudamos empresas a escalar através de tecnologia e automação inteligente.</p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center md:justify-start">
                    <a href="{{ route('contact') }}" class="px-8 py-4 bg-white text-indigo-600 font-bold rounded-xl hover:bg-indigo-50 transition-all text-center">
                        Solicitar Consultoria Gratuita
                    </a>
                    <a href="{{ route('services.index') }}" class="px-8 py-4 bg-indigo-500/20 border border-white/30 backdrop-blur-sm text-white font-bold rounded-xl hover:bg-white/10 transition-all text-center">
                        Ver Nossos Serviços
                    </a>
                </div>
            </div>
        </div>

        <div class="mt-16 pt-10 border-t border-slate-800 flex items-center justify-between">
            <p class="text-slate-400 font-medium">Gostou deste artigo? Compartilhe!</p>
            <div class="flex space-x-3">
                <button class="w-10 h-10 rounded-full bg-slate-800 hover:bg-indigo-600 hover:text-white text-slate-400 flex items-center justify-center transition-colors">
                    <span class="sr-only">Copiar link</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                </button>
                <a href="#" class="w-10 h-10 rounded-full bg-slate-800 hover:bg-blue-600 hover:text-white text-slate-400 flex items-center justify-center transition-colors">
                    <span class="sr-only">LinkedIn</span>
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                </a>
            </div>
        </div>
    </article>
</div>
@endsection
