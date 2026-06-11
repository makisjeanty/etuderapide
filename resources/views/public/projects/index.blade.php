@extends('layouts.public')

@section('title', 'Portfólio | ' . config('app.name', 'Etude Rapide'))

@section('content')
<div class="max-w-7xl mx-auto px-6 lg:px-8 py-20">
    <div class="text-center mb-16">
        <h1 class="text-4xl md:text-5xl font-bold font-heading mb-6 text-slate-dark">Nosso Portfólio</h1>
        <p class="text-lg md:text-xl text-slate-medium max-w-2xl mx-auto">Casos de estudo e projetos que demonstram nossa capacidade de execução e qualidade técnica.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
        @forelse($projects as $project)
            <a href="{{ route('projects.show', $project->slug) }}" class="group block bg-white border border-slate-dark/10 p-5 rounded-3xl transition-all duration-300 hover:border-slate-dark/20 hover:shadow-sm">
                <div class="relative overflow-hidden rounded-2xl aspect-video bg-ivory-medium border border-slate-dark/10 mb-6">
                    @if($project->featured_image)
                        <img src="{{ $project->featured_image }}" alt="{{ $project->title }}" class="w-full h-full object-cover group-hover:scale-102 transition-transform duration-500">
                    @else
                        <div class="absolute inset-0 flex items-center justify-center text-slate-light text-sm font-medium">Sem imagem</div>
                    @endif
                    
                    @if($project->is_featured)
                        <div class="absolute top-4 right-4 z-10">
                            <span class="px-3.5 py-1 bg-clay text-white text-[10px] uppercase font-bold rounded-full shadow-sm tracking-wider">Destaque</span>
                        </div>
                    @endif

                    @if($project->category)
                        <div class="absolute top-4 left-4 z-10">
                            <span class="px-3.5 py-1 bg-slate-dark border border-slate-dark/10 rounded-lg text-[10px] font-bold text-white uppercase tracking-wider">
                                {{ $project->category->name }}
                            </span>
                        </div>
                    @endif
                </div>
                
                <div class="px-2 pb-2">
                    <h2 class="text-2xl font-bold text-slate-dark mb-3 group-hover:text-clay transition-colors">{{ $project->title }}</h2>
                    <p class="text-slate-medium text-sm mb-4 line-clamp-2 leading-relaxed">{{ $project->summary }}</p>
                    
                    @if($project->tech_stack && count($project->tech_stack) > 0)
                        <div class="flex flex-wrap gap-2 mt-4">
                            @foreach(array_slice($project->tech_stack, 0, 3) as $tech)
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-md bg-oat border border-slate-dark/10 text-slate-dark">
                                    {{ $tech }}
                                </span>
                            @endforeach
                            @if(count($project->tech_stack) > 3)
                                <span class="px-2 py-1 text-xs font-bold text-slate-light self-center">
                                    +{{ count($project->tech_stack) - 3 }}
                                </span>
                            @endif
                        </div>
                    @endif
                </div>
            </a>
        @empty
            <div class="col-span-2 text-center py-20 text-slate-light italic bg-white border border-slate-dark/10 rounded-3xl">Nenhum projeto publicado no momento.</div>
        @endforelse
    </div>
    
    @if(method_exists($projects, 'links'))
        <div class="mt-16">
            {{ $projects->links() }}
        </div>
    @endif
</div>
@endsection
