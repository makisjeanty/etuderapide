@extends('layouts.public')

@section('title', 'Nossos Serviços | Makis Digital')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
    <div class="text-center mb-16">
        <h1 class="text-4xl md:text-5xl font-bold font-heading mb-6 text-white">Serviços Especializados</h1>
        <p class="text-xl text-slate-400 max-w-2xl mx-auto">Soluções tecnológicas sob medida para escalar o seu negócio e otimizar processos.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        @forelse($services as $service)
            <a href="{{ route('services.show', $service->slug) }}" class="glass-panel p-8 rounded-3xl group hover:-translate-y-2 hover:shadow-[0_20px_40px_-15px_rgba(99,102,241,0.2)] transition-all flex flex-col h-full relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-500/10 rounded-bl-full -mr-16 -mt-16 transition-transform group-hover:scale-150 duration-500"></div>
                
                <div class="flex justify-between items-start mb-6">
                    <h2 class="text-2xl font-bold text-white group-hover:text-indigo-300 transition-colors">{{ $service->name }}</h2>
                    @if($service->price_from)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-slate-800 text-slate-300 border border-slate-700">
                            A partir de R$ {{ number_format($service->price_from, 2, ',', '.') }}
                        </span>
                    @endif
                </div>
                
                <p class="text-slate-400 mb-8 flex-grow leading-relaxed">{{ $service->short_description }}</p>
                
                <div class="mt-auto pt-6 border-t border-slate-800/50 flex items-center justify-between text-sm font-medium">
                    <span class="text-slate-500">{{ $service->category?->name ?? 'Geral' }}</span>
                    <span class="text-indigo-400 flex items-center">
                        Ver detalhes 
                        <svg class="w-4 h-4 ml-1 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </span>
                </div>
            </a>
        @empty
            <div class="col-span-2 text-center py-20 text-slate-500 glass-panel rounded-3xl">Nenhum serviço disponível no momento.</div>
        @endforelse
    </div>
    
    @if(method_exists($services, 'links'))
        <div class="mt-12">
            {{ $services->links() }}
        </div>
    @endif
</div>
@endsection
