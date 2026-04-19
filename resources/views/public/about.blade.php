@extends('layouts.public')

@section('title', 'Sobre Nós | Makis Digital')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
    <div class="text-center mb-16">
        <h1 class="text-4xl md:text-5xl font-bold font-heading mb-6 text-white">Sobre Nós</h1>
        <p class="text-xl text-slate-400">Nossa missão, visão e a equipe por trás da mágica.</p>
    </div>

    <div class="glass-panel rounded-3xl p-8 md:p-12 prose prose-invert prose-indigo max-w-none">
        <h2>Quem Somos</h2>
        <p>Somos um estúdio de desenvolvimento focado em criar aplicações web de altíssimo nível. Acreditamos que a união de design excepcional, arquitetura sólida e automação inteligente é a chave para destacar negócios na era digital.</p>
        
        <h2>Nossa Missão</h2>
        <p>Entregar produtos digitais que não apenas funcionem perfeitamente, mas que surpreendam os usuários a cada interação. Utilizamos as tecnologias mais modernas, como Laravel, Vue/Alpine, Bun e Python (para IA), garantindo escalabilidade e inovação.</p>
        
        <h2>Por que nos escolher?</h2>
        <ul>
            <li><strong>Foco em Performance:</strong> Cada milissegundo importa. Nossas stacks são otimizadas.</li>
            <li><strong>Design Premium:</strong> Interfaces em Glassmorphism, tipografia pensada e micro-interações.</li>
            <li><strong>IA Integrada:</strong> Não somos apenas desenvolvedores, criamos soluções inteligentes que aceleram processos.</li>
        </ul>
    </div>
</div>
@endsection
