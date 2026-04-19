@extends('layouts.public')

@section('title', 'Blog | Makis Digital')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
    <div class="text-center mb-16">
        <h1 class="text-4xl md:text-5xl font-bold font-heading mb-6 text-white">Nosso Blog</h1>
        <p class="text-xl text-slate-400 max-w-2xl mx-auto">Insights, tutoriais e novidades sobre desenvolvimento, design e inteligência artificial.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($posts as $post)
            <a href="{{ route('blog.show', $post->slug) }}" class="group block glass-panel p-4 rounded-3xl transition-all hover:-translate-y-2 hover:shadow-[0_20px_40px_-15px_rgba(99,102,241,0.2)] hover:border-indigo-500/30 flex flex-col h-full">
                <div class="relative overflow-hidden rounded-2xl mb-6 aspect-video bg-slate-800">
                    @if($post->featured_image)
                        <img src="{{ $post->featured_image }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                        <div class="absolute inset-0 flex items-center justify-center text-slate-600 font-medium">Sem imagem</div>
                    @endif
                    @if($post->category)
                        <div class="absolute top-4 left-4">
                            <span class="px-3 py-1 bg-indigo-600/90 backdrop-blur-md rounded-lg text-[10px] font-bold text-white uppercase tracking-wider">
                                {{ $post->category->name }}
                            </span>
                        </div>
                    @endif
                </div>

                <div class="text-sm font-medium text-slate-400 mb-4 flex items-center gap-2">
                    <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    {{ $post->published_at ? $post->published_at->translatedFormat('d de F, Y') : 'Em Breve' }}
                </div>
                
                <h2 class="text-xl font-bold text-white mb-4 group-hover:text-indigo-300 transition-colors line-clamp-2">
                    {{ $post->title }}
                </h2>
                
                <p class="text-slate-400 mb-6 line-clamp-3 flex-grow">
                    {{ Str::limit(strip_tags($post->body), 150) }}
                </p>
                
                <div class="flex items-center justify-between pt-4 border-t border-slate-800/50 mt-auto">
                    <div class="flex items-center text-sm text-slate-500">
                        @if($post->author)
                            <div class="w-6 h-6 rounded-full bg-slate-800 flex items-center justify-center text-xs mr-2 border border-slate-700">
                                {{ substr($post->author->name, 0, 1) }}
                            </div>
                            {{ $post->author->name }}
                        @else
                            Equipe
                        @endif
                    </div>
                    <span class="text-indigo-400 text-sm font-medium flex items-center">
                        Ler <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </span>
                </div>
            </a>
        @empty
            <div class="col-span-3 text-center py-20 text-slate-500 glass-panel rounded-3xl">Nenhum artigo publicado no momento. Volte em breve!</div>
        @endforelse
    </div>
    
    @if(method_exists($posts, 'links'))
        <div class="mt-16">
            {{ $posts->links() }}
        </div>
    @endif
</div>
@endsection
