@extends('layouts.public')

@section('title', 'Sobre Nós | ' . config('app.name', 'Etude Rapide'))

@section('content')
<div class="max-w-4xl mx-auto px-6 lg:px-8 py-20">
    <div class="text-center mb-16">
        <h1 class="text-4xl md:text-5xl font-bold font-heading mb-6 text-slate-dark">Sobre Nós</h1>
        <p class="text-lg md:text-xl text-slate-medium">Nossa missão, visão e a equipe por trás da mágica.</p>
    </div>

    <div class="bg-white border border-slate-dark/10 rounded-3xl p-8 md:p-12 shadow-sm prose prose-slate max-w-none">
        <h2 class="text-2xl font-bold text-slate-dark mb-4">Quem Somos</h2>
        <p class="text-slate-medium leading-relaxed mb-8">
            Somos um estúdio de desenvolvimento focado em criar aplicações web de altíssimo nível. Acreditamos que a união de design excepcional, arquitetura sólida e automação inteligente é a chave para destacar negócios na era digital.
        </p>
        
        <h2 class="text-2xl font-bold text-slate-dark mb-4">Nossa Missão</h2>
        <p class="text-slate-medium leading-relaxed mb-8">
            Entregar produtos digitais que não apenas funcionem perfeitamente, mas que surpreendam os usuários a cada interação. Utilizamos as tecnologias mais modernas, como Laravel, Vue/Alpine, Bun e Python (para IA), garantindo escalabilidade e inovação.
        </p>
        
        <h2 class="text-2xl font-bold text-slate-dark mb-4">Por que nos escolher?</h2>
        <ul class="space-y-4 text-slate-medium">
            <li class="flex items-start">
                <span class="text-clay mr-3 font-bold text-lg select-none">✦</span>
                <div>
                    <strong class="text-slate-dark">Foco em Performance:</strong> Cada milissegundo importa. Nossas stacks são otimizadas.
                </div>
            </li>
            <li class="flex items-start">
                <span class="text-clay mr-3 font-bold text-lg select-none">✦</span>
                <div>
                    <strong class="text-slate-dark">Design Premium:</strong> Interfaces sofisticadas com tipografia pensada, cores elegantes e micro-interações.
                </div>
            </li>
            <li class="flex items-start">
                <span class="text-clay mr-3 font-bold text-lg select-none">✦</span>
                <div>
                    <strong class="text-slate-dark">IA Integrada:</strong> Não somos apenas desenvolvedores, criamos soluções inteligentes que aceleram processos.
                </div>
            </li>
        </ul>
    </div>
</div>
@endsection
