@extends('layouts.public')

@section('title', 'Makis Digital | Soluções Digitais')

@section('content')
<!-- Hero Section -->
<div class="relative overflow-hidden pt-16 pb-32">
    <!-- Background Image with Overlay -->
    <div class="absolute inset-0 z-0">
        <img src="{{ asset('images/hero.png') }}" class="w-full h-full object-cover opacity-20" alt="Hero Background">
        <div class="absolute inset-0 bg-gradient-to-b from-slate-950 via-slate-950/80 to-slate-950"></div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center max-w-3xl mx-auto">
            <div class="inline-flex items-center px-4 py-2 rounded-full border border-indigo-500/30 bg-indigo-500/10 text-indigo-300 text-sm font-medium mb-8">
                <span class="flex w-2 h-2 rounded-full bg-indigo-400 mr-2 animate-pulse"></span>
                Inovação e Performance
            </div>
            <h1 class="text-5xl md:text-7xl font-bold font-heading mb-6 tracking-tight">
                Transformamos sua expertise em <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-purple-400">lucro digital</span>.
            </h1>
            <p class="text-xl text-slate-400 mb-10 leading-relaxed">
                Não criamos apenas sites. Construímos ecossistemas digitais de alta performance com IA que captam leads e automatizam suas vendas enquanto você foca no que importa.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('services.index') }}" class="w-full sm:w-auto px-8 py-4 rounded-xl font-medium text-white bg-indigo-600 hover:bg-indigo-500 shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40 transition-all flex items-center justify-center group">
                    Quero Mais Clientes
                    <svg class="ml-2 w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
                <a href="{{ route('contact') }}" class="w-full sm:w-auto px-8 py-4 rounded-xl font-medium text-slate-300 bg-white/5 border border-white/10 hover:bg-white/10 transition-all flex items-center justify-center">
                    Solicitar Análise Gratuita
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Services Showcase -->
<div class="py-24 bg-slate-900/50 border-y border-slate-800/50 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold font-heading mb-4 text-white">Como podemos ajudar</h2>
            <p class="text-slate-400 max-w-2xl mx-auto">Nossos serviços são desenhados para alavancar sua presença digital com tecnologia de ponta.</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($services as $service)
                <a href="{{ route('services.show', $service->slug) }}" class="glass-panel p-8 rounded-2xl group hover:-translate-y-1 hover:shadow-2xl hover:shadow-indigo-500/10 transition-all block">
                    <div class="w-12 h-12 rounded-lg bg-indigo-500/20 flex items-center justify-center mb-6 text-indigo-400 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">{{ $service->name }}</h3>
                    <p class="text-slate-400 text-sm mb-6 line-clamp-3">{{ $service->short_description }}</p>
                    <div class="flex items-center text-indigo-400 text-sm font-medium">
                        Saiba mais <span class="ml-1 group-hover:translate-x-1 transition-transform">&rarr;</span>
                    </div>
                </a>
            @empty
                <div class="col-span-3 text-center py-12 text-slate-500">Nenhum serviço cadastrado ainda.</div>
            @endforelse
        </div>
        
        <div class="mt-12 text-center">
            <a href="{{ route('services.index') }}" class="text-slate-400 hover:text-white font-medium transition-colors">Ver todos os serviços &rarr;</a>
        </div>
    </div>
</div>

<!-- Featured Projects -->
<div class="py-24 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-end mb-16 gap-6">
            <div>
                <h2 class="text-3xl md:text-4xl font-bold font-heading mb-4 text-white">Trabalhos em Destaque</h2>
                <p class="text-slate-400 max-w-xl">Uma seleção dos nossos melhores projetos e casos de sucesso.</p>
            </div>
            <a href="{{ route('projects.index') }}" class="px-6 py-3 rounded-xl border border-slate-700 hover:bg-slate-800 text-white transition-all text-sm font-medium whitespace-nowrap">
                Ver Portfólio Completo
            </a>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @forelse($featuredProjects as $project)
                <a href="{{ route('projects.show', $project->slug) }}" class="group block">
                    <div class="relative overflow-hidden rounded-2xl mb-6 aspect-video bg-slate-800">
                        @if($project->featured_image)
                            <img src="{{ $project->featured_image }}" alt="{{ $project->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="absolute inset-0 flex items-center justify-center text-slate-600 font-medium">Sem imagem</div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-6">
                            <span class="px-3 py-1 bg-white/20 backdrop-blur-md rounded-lg text-xs font-medium text-white">Visualizar Estudo de Caso</span>
                        </div>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2 group-hover:text-indigo-400 transition-colors">{{ $project->title }}</h3>
                    <p class="text-slate-400 text-sm line-clamp-2">{{ $project->summary }}</p>
                </a>
            @empty
                <div class="col-span-3 text-center py-12 text-slate-500">Nenhum projeto em destaque ainda.</div>
            @endforelse
        </div>
    </div>
</div>

<div class="py-24 relative overflow-hidden">
    <!-- Efeito de fundo sutil -->
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[400px] bg-indigo-500/5 rounded-full blur-[120px]"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold font-heading mb-4 text-white">O que dizem nossos clientes</h2>
            <p class="text-slate-400 max-w-2xl mx-auto">Resultados reais entregues para empresas que confiaram na Makis Digital.</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @forelse($testimonials as $testimonial)
                <div class="glass-panel p-8 rounded-3xl border-white/5 relative flex flex-col h-full">
                    <!-- Aspas -->
                    <div class="absolute top-6 right-8 text-indigo-500/20">
                        <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21L14.017 18C14.017 16.8954 14.9124 16 16.017 16H19.017C19.5693 16 20.017 15.5523 20.017 15V9C20.017 8.44772 19.5693 8 19.017 8H16.017C15.4647 8 15.017 8.44772 15.017 9V12C15.017 12.5523 14.5693 13 14.017 13H13.017V21H14.017ZM6.017 21L6.017 18C6.017 16.8954 6.91243 16 8.017 16H11.017C11.5693 16 12.017 15.5523 12.017 15V9C12.017 8.44772 11.5693 8 11.017 8H8.017C7.46472 8 7.017 8.44772 7.017 9V12C7.017 12.5523 6.56929 13 6.017 13H5.017V21H6.017Z"></path></svg>
                    </div>
                    
                    <!-- Estrelas -->
                    <div class="flex text-yellow-500 mb-6">
                        @for($i = 0; $i < $testimonial->rating; $i++)
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        @endfor
                    </div>
                    
                    <p class="text-slate-300 mb-8 leading-relaxed italic">
                        "{{ $testimonial->content }}"
                    </p>
                    
                    <div class="mt-auto flex items-center">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center text-white font-bold text-sm">
                            {{ substr($testimonial->client_name, 0, 1) }}
                        </div>
                        <div class="ml-4">
                            <h4 class="text-white font-bold text-sm">{{ $testimonial->client_name }}</h4>
                            <p class="text-slate-500 text-xs">{{ $testimonial->role }} @ {{ $testimonial->company_name }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-12 text-slate-500 italic">"Excelência é o nosso padrão de entrega." - Equipe Makis Digital</div>
            @endforelse
        </div>
    </div>
</div>

<!-- IA Business Auditor (Lead Magnet) -->
<div class="py-24 bg-slate-900/80 border-y border-indigo-500/10 relative overflow-hidden" x-data="aiAuditor('{{ route('ai.analyze') }}')">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div>
                <div class="inline-flex items-center px-3 py-1 rounded-full bg-indigo-500/10 text-indigo-400 text-xs font-bold mb-6 border border-indigo-500/20">
                    NOVA FERRAMENTA
                </div>
                <h2 class="text-4xl md:text-5xl font-bold font-heading text-white mb-6 leading-tight">
                    Sua empresa está pronta para a <span class="text-indigo-400">Era da IA?</span>
                </h2>
                <p class="text-lg text-slate-400 mb-8">
                    Insira o URL do seu site ou descreva seu processo de vendas. Nossa IA vai analisar e gerar um relatório gratuito de oportunidades de automação em segundos.
                </p>

                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="p-2 bg-indigo-500/20 rounded-lg mr-4 text-indigo-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <p class="text-slate-300">Análise de custos operacionais.</p>
                    </div>
                    <div class="flex items-start">
                        <div class="p-2 bg-indigo-500/20 rounded-lg mr-4 text-indigo-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <p class="text-slate-300">Oportunidades de automação de vendas.</p>
                    </div>
                </div>
            </div>

            <div class="relative">
                <div class="glass-panel p-8 rounded-3xl border-indigo-500/30 shadow-2xl shadow-indigo-500/10">
                    <div x-show="!result">
                        <div style="display: none;">
                            <input type="text" x-model="website_url" tabindex="-1" autocomplete="off">
                        </div>
                        <div class="mb-6">
                            <label class="block text-sm font-bold text-slate-400 mb-2">Descreva seu negócio ou cole o URL:</label>
                            <textarea x-model="text" class="w-full bg-slate-950/50 border border-slate-800 rounded-2xl p-4 text-white focus:ring-2 focus:ring-indigo-500 transition-all outline-none" rows="3" placeholder="Ex: Minha empresa vende serviços de consultoria e temos dificuldade em qualificar leads..."></textarea>
                        </div>
                        
                        <div class="mb-6">
                            <label class="block text-sm font-bold text-slate-400 mb-2">Seu melhor E-mail (para receber o relatório):</label>
                            <input type="email" x-model="email" class="w-full bg-slate-950/50 border border-slate-800 rounded-2xl p-4 text-white focus:ring-2 focus:ring-indigo-500 transition-all outline-none" placeholder="nome@empresa.com">
                        </div>

                        <button @click="runAudit()" :disabled="loading" class="w-full py-4 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-2xl shadow-lg shadow-indigo-500/40 transition-all flex items-center justify-center">
                            <template x-if="!loading">
                                <span>Gerar Auditoria Gratuita 🚀</span>
                            </template>
                            <template x-if="loading">
                                <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            </template>
                        </button>
                    </div>

                    <!-- Resultado da Auditoria -->
                    <div x-show="result" x-transition class="space-y-6">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-bold text-white">Relatório de IA</h3>
                            <div class="px-3 py-1 bg-green-500/20 text-green-400 rounded-lg text-xs font-bold border border-green-500/20">SCORE: <span x-text="result.score"></span>/100</div>
                        </div>
                        
                        <div class="p-4 bg-indigo-500/10 border border-indigo-500/20 rounded-2xl">
                            <p class="text-sm text-indigo-300 italic" x-text="result.verdict"></p>
                        </div>

                        <div x-show="error" class="p-4 bg-red-500/10 border border-red-500/20 rounded-2xl text-sm text-red-300" x-text="error"></div>

                        <div>
                            <h4 class="text-sm font-bold text-slate-400 mb-3 uppercase tracking-widest">Oportunidades:</h4>
                            <ul class="space-y-2">
                                <template x-for="opt in result.opportunities">
                                    <li class="flex items-start text-sm text-slate-300">
                                        <span class="text-indigo-500 mr-2">✦</span>
                                        <span x-text="opt"></span>
                                    </li>
                                </template>
                            </ul>
                        </div>
                        
                        <div class="flex flex-col gap-3">
                            <a x-show="result.report_url" :href="result.report_url" target="_blank" class="w-full py-4 bg-white hover:bg-slate-100 text-slate-900 font-bold rounded-2xl shadow-lg transition-all flex items-center justify-center">
                                <span>Baixar Diagnóstico Completo (PDF) 📄</span>
                            </a>
                            
                            <button @click="result = null" class="w-full py-3 text-slate-500 hover:text-slate-300 text-sm font-medium transition-colors">
                                Nova análise &circlearrowright;
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Blog Teaser -->
<div class="py-24 bg-slate-900/50 border-t border-slate-800/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-bold font-heading mb-4 text-white">Últimos Artigos</h2>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @forelse($latestPosts as $post)
                <div class="glass-panel p-6 rounded-2xl flex flex-col">
                    <div class="text-xs text-indigo-400 font-medium mb-3">{{ $post->published_at?->format('d M, Y') ?? 'Em breve' }}</div>
                    <a href="{{ route('blog.show', $post->slug) }}" class="block flex-grow">
                        <h3 class="text-lg font-bold text-white mb-3 hover:text-indigo-300 transition-colors">{{ $post->title }}</h3>
                        <p class="text-slate-400 text-sm line-clamp-3 mb-6">{{ Str::limit(strip_tags($post->body), 100) }}</p>
                    </a>
                    <a href="{{ route('blog.show', $post->slug) }}" class="text-sm font-medium text-slate-300 hover:text-white mt-auto">Ler artigo completo &rarr;</a>
                </div>
            @empty
                <div class="col-span-3 text-center py-12 text-slate-500">Nenhum artigo publicado ainda.</div>
            @endforelse
        </div>
    </div>
</div>

<!-- Lead Capture / Newsletter -->
<div class="py-24 relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="glass-panel rounded-[3rem] p-8 md:p-16 relative overflow-hidden bg-gradient-to-br from-indigo-600/20 to-purple-600/20 border-indigo-500/30">
            <div class="absolute top-0 right-0 -mt-10 -mr-10 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-64 h-64 bg-purple-500/10 rounded-full blur-3xl"></div>
            
            <div class="relative z-10 grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-3xl md:text-5xl font-bold font-heading text-white mb-6 leading-tight">
                        Receba estratégias de <span class="text-indigo-400">automação e IA</span> direto no seu e-mail.
                    </h2>
                    <p class="text-lg text-slate-300">
                        Junte-se a outros empresários que estão escalando seus negócios com tecnologia. Zero spam, apenas valor.
                    </p>
                </div>
                <div>
                    <form action="{{ route('contact.submit') }}" method="POST" class="flex flex-col sm:flex-row gap-3">
                        @csrf
                        <input type="hidden" name="name" value="Inscrito Newsletter">
                        <input type="hidden" name="message" value="Inscrição via Newsletter da Home">
                        <input type="email" name="email" placeholder="Seu melhor e-mail" class="flex-grow bg-slate-900/50 border border-indigo-500/30 rounded-2xl p-4 text-white focus:ring-2 focus:ring-indigo-500 transition-all outline-none" required>
                        <button type="submit" class="px-8 py-4 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-2xl shadow-xl shadow-indigo-500/40 transition-all whitespace-nowrap">
                            Quero me inscrever
                        </button>
                    </form>
                    <p class="mt-4 text-xs text-slate-500 text-center lg:text-left">
                        Ao se inscrever, você concorda com nossa política de privacidade.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
