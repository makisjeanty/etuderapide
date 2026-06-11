@extends('layouts.public')

@section('title', 'Nossos Serviços | ' . config('app.name', 'Etude Rapide'))

@section('content')
<div class="max-w-7xl mx-auto px-6 lg:px-8 py-20">
    <div class="text-center mb-16">
        <h1 class="text-4xl md:text-5xl font-bold font-heading mb-6 text-slate-dark">Serviços Especializados</h1>
        <p class="text-lg md:text-xl text-slate-medium max-w-2xl mx-auto">Soluções tecnológicas sob medida para escalar o seu negócio e otimizar processos.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        @forelse($services as $service)
            <a href="{{ route('services.show', $service->slug) }}" class="ant-card p-8 group hover:-translate-y-1 transition-all duration-300 flex flex-col h-full relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-oat/20 rounded-bl-full -mr-16 -mt-16 transition-transform group-hover:scale-110 duration-500"></div>
                
                <div class="flex flex-wrap justify-between items-start mb-6 gap-3 z-10">
                    <h2 class="text-2xl font-bold text-slate-dark group-hover:text-clay transition-colors">{{ $service->name }}</h2>
                    @if($service->price_from)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-clay/5 text-clay border border-clay/20">
                            A partir de R$ {{ number_format($service->price_from, 2, ',', '.') }}
                        </span>
                    @endif
                </div>
                
                <p class="text-slate-medium mb-8 flex-grow leading-relaxed z-10">{{ $service->short_description }}</p>
                
                <div class="mt-auto pt-6 border-t border-slate-dark/10 flex items-center justify-between text-sm font-medium z-10">
                    <span class="text-slate-light font-semibold">{{ $service->category?->name ?? 'Geral' }}</span>
                    <span class="text-clay group-hover:text-accent flex items-center font-bold">
                        Ver detalhes 
                        <svg class="w-4 h-4 ml-1 group-hover:translate-x-1.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </span>
                </div>
            </a>
        @empty
            <div class="col-span-2 text-center py-20 text-slate-light italic bg-white border border-slate-dark/10 rounded-3xl">Nenhum serviço disponível no momento.</div>
        @endforelse
    </div>
    
    @if(method_exists($services, 'links'))
        <div class="mt-12">
            {{ $services->links() }}
        </div>
    @endif
</div>
@endsection
