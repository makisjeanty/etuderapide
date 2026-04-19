@extends('layouts.public')

@section('title', 'Portfólio | Makis Digital')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
    <div class="text-center mb-16">
        <h1 class="text-4xl md:text-5xl font-bold font-heading mb-6 text-white">Nosso Portfólio</h1>
        <p class="text-xl text-slate-400 max-w-2xl mx-auto">Casos de estudo e projetos que demonstram nossa capacidade de execução e qualidade técnica.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
        @forelse($projects as $project)
            <a href="{{ route('projects.show', $project->slug) }}" class="group block glass-panel p-4 rounded-3xl transition-all hover:border-indigo-500/30 hover:shadow-2xl hover:shadow-indigo-500/10">
                <div class="relative overflow-hidden rounded-2xl aspect-video bg-slate-800 mb-6">
                    @if($project->featured_image)
                        <img src="{{ $project->featured_image }}" alt="{{ $project->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                    @else
                        <div class="absolute inset-0 flex items-center justify-center text-slate-600 font-medium">Sem imagem</div>
                    @endif
                    
                    @if($project->is_featured)
                        <div class="absolute top-4 right-4 z-10">
                            <span class="px-3 py-1 bg-yellow-500/90 text-yellow-50 text-[10px] uppercase font-bold rounded-full shadow-lg backdrop-blur-sm tracking-widest">Destaque</span>
                        </div>
                    @endif

                    @if($project->category)
                        <div class="absolute top-4 left-4 z-10">
                            <span class="px-3 py-1 bg-indigo-600/90 backdrop-blur-md rounded-lg text-[10px] font-bold text-white uppercase tracking-wider">
                                {{ $project->category->name }}
                            </span>
                        </div>
                    @endif
                </div>
                
                <div class="px-2 pb-4">
                    <h2 class="text-2xl font-bold text-white mb-3 group-hover:text-indigo-400 transition-colors">{{ $project->title }}</h2>
                    <p class="text-slate-400 mb-4 line-clamp-2">{{ $project->summary }}</p>
                    
                    @if($project->tech_stack && count($project->tech_stack) > 0)
                        <div class="flex flex-wrap gap-2 mt-4">
                            @foreach(array_slice($project->tech_stack, 0, 3) as $tech)
                                <span class="px-2 py-1 text-xs font-medium rounded-md bg-slate-800 border border-slate-700 text-slate-300">
                                    {{ $tech }}
                                </span>
                            @endforeach
                            @if(count($project->tech_stack) > 3)
                                <span class="px-2 py-1 text-xs font-medium rounded-md text-slate-500">
                                    +{{ count($project->tech_stack) - 3 }}
                                </span>
                            @endif
                        </div>
                    @endif
                </div>
            </a>
        @empty
            <div class="col-span-2 text-center py-20 text-slate-500 glass-panel rounded-3xl">Nenhum projeto publicado no momento.</div>
        @endforelse
    </div>
    
    @if(method_exists($projects, 'links'))
        <div class="mt-16">
            {{ $projects->links() }}
        </div>
    @endif
</div>
@endsection
