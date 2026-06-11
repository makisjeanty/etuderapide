<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>@yield('title', config('app.name', 'Makis Digital'))</title>
    <meta name="description" content="@yield('meta_description', 'Soluções digitais de alta performance com IA.')">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        :root {
            --ivory-light:  #faf9f5;
            --ivory-medium: #f0eee6;
            --ivory-dark:   #e8e6dc;
            --slate-dark:   #141413;
            --slate-medium: #3d3d3a;
            --slate-light:  #5e5d59;
            --cloud-light:  #d1cfc5;
            --clay:         #b85a34;
            --accent:       #9e4a28;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--ivory-light);
            color: var(--slate-dark);
        }

        h1, h2, h3, h4, h5, h6, .font-heading {
            font-family: 'Outfit', sans-serif;
        }

        /* Selection */
        ::selection { background: rgba(204,120,92,.4); }

        /* Anthropic-style nav — ivory with border bottom */
        .ant-nav {
            background-color: rgba(250,249,245,0.90);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(20,20,19,0.08);
        }

        /* Card style — white with slate border */
        .ant-card {
            background-color: #ffffff;
            border: 1px solid rgba(20,20,19,0.10);
            border-radius: 0.75rem;
        }

        .ant-card:hover {
            border-color: rgba(20,20,19,0.20);
            box-shadow: 0 4px 24px rgba(20,20,19,0.06);
        }

        /* Section stripe */
        .stripe-ivory { background-color: var(--ivory-medium); }
        .stripe-oat   { background-color: #e3dacc; }

        /* Clay pill badge */
        .ant-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.2rem 0.75rem;
            border-radius: 9999px;
            border: 1px solid rgba(184,90,52,0.30);
            background: rgba(184,90,52,0.08);
            color: #9e4a28;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        /* Primary button — slate-dark bg, ivory text (Anthropic style) */
        .btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.75rem 1.75rem;
            background-color: var(--slate-dark);
            color: var(--ivory-light);
            border: 1px solid var(--slate-dark);
            border-radius: 0.5rem;
            font-weight: 600;
            font-size: 0.9375rem;
            transition: background-color 0.2s, border-color 0.2s;
            text-decoration: none;
        }
        .btn-primary:hover {
            background-color: var(--slate-medium);
            border-color: var(--slate-medium);
            color: var(--ivory-light);
        }

        /* Secondary button — outlined */
        .btn-secondary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.75rem 1.75rem;
            background-color: transparent;
            color: var(--slate-dark);
            border: 1px solid rgba(20,20,19,0.25);
            border-radius: 0.5rem;
            font-weight: 600;
            font-size: 0.9375rem;
            transition: background-color 0.2s, border-color 0.2s;
            text-decoration: none;
        }
        .btn-secondary:hover {
            border-color: var(--slate-dark);
            background-color: rgba(20,20,19,0.04);
        }

        /* Clay CTA button */
        .btn-clay {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.75rem 1.75rem;
            background-color: var(--clay);
            color: #fff;
            border: 1px solid var(--clay);
            border-radius: 0.5rem;
            font-weight: 600;
            font-size: 0.9375rem;
            transition: background-color 0.2s;
            text-decoration: none;
        }
        .btn-clay:hover {
            background-color: var(--accent);
            border-color: var(--accent);
        }

        /* Divider line */
        .ant-divider { border-color: rgba(20,20,19,0.08); }

        /* Respect reduced-motion preference */
        @media (prefers-reduced-motion: reduce) {
            .animate-pulse, .animate-ping, .animate-bounce, .animate-spin {
                animation: none !important;
            }
            * {
                transition-duration: 0.01ms !important;
            }
        }

        /* Smooth underline link */
        .ant-link {
            color: var(--slate-dark);
            text-decoration: underline;
            text-decoration-thickness: 1px;
            text-underline-offset: 0.2em;
            transition: color 0.2s;
        }
        .ant-link:hover { color: var(--slate-light); }
    </style>
</head>
<body class="antialiased min-h-screen flex flex-col">

    <!-- Skip to main content -->
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-2 focus:left-2 focus:z-[9999] focus:bg-white focus:text-slate-900 focus:px-4 focus:py-2 focus:rounded focus:shadow-lg focus:outline-none">
        Ir para o conteúdo principal
    </a>

    <!-- Navigation — Anthropic style: ivory, minimal, clean -->
    <nav class="fixed top-0 w-full z-[9999] ant-nav" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex justify-between items-center h-[4.25rem]">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-2.5 group">
                    <div class="w-7 h-7 rounded-lg bg-clay flex items-center justify-center transition-opacity group-hover:opacity-80" style="background-color:#d97757">
                        <svg class="w-4 h-4" fill="none" stroke="#faf9f5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <span class="font-heading font-semibold text-lg tracking-tight" style="color:#141413">
                        {{ config('app.name', 'Makis Digital') }}
                    </span>
                </a>

                <!-- Desktop Links -->
                <div class="hidden md:flex items-center gap-1">
                    @foreach([
                        ['route' => 'home',           'label' => 'Início',     'match' => 'home'],
                        ['route' => 'about',          'label' => 'Sobre',      'match' => 'about'],
                        ['route' => 'services.index', 'label' => 'Serviços',   'match' => 'services.*'],
                        ['route' => 'projects.index', 'label' => 'Portfólio',  'match' => 'projects.*'],
                        ['route' => 'blog.index',     'label' => 'Blog',       'match' => 'blog.*'],
                        ['route' => 'contact',        'label' => 'Contato',    'match' => 'contact'],
                    ] as $item)
                        <a href="{{ route($item['route']) }}"
                           class="px-3.5 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs($item['match']) ? 'text-slate-dark bg-slate-dark/6' : 'text-slate-light hover:text-slate-dark hover:bg-slate-dark/5' }}"
                           style="{{ request()->routeIs($item['match']) ? 'color:#141413;background:rgba(20,20,19,0.06)' : 'color:#5e5d59' }}">
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </div>

                <!-- CTA -->
                <div class="hidden md:block">
                    <a href="{{ route('contact') }}" class="btn-primary text-sm py-2.5 px-5">
                        Falar Conosco
                    </a>
                </div>

                <!-- Mobile toggle -->
                <button @click="mobileMenuOpen = !mobileMenuOpen"
                        class="md:hidden p-2 rounded-md"
                        style="color:#141413"
                        :aria-label="mobileMenuOpen ? 'Fechar menu' : 'Abrir menu'"
                        :aria-expanded="mobileMenuOpen.toString()"
                        aria-controls="mobile-menu">
                    <svg x-show="!mobileMenuOpen" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg x-show="mobileMenuOpen" style="display:none" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile menu -->
        <div id="mobile-menu" x-show="mobileMenuOpen" style="display:none" class="md:hidden border-t ant-divider" x-transition>
            <div class="px-6 pt-2 pb-5 space-y-1" style="background-color:#faf9f5">
                <a href="{{ route('home') }}"           class="block px-3 py-2.5 rounded-md text-sm font-medium" style="color:#141413">Início</a>
                <a href="{{ route('about') }}"          class="block px-3 py-2.5 rounded-md text-sm font-medium" style="color:#5e5d59">Sobre</a>
                <a href="{{ route('services.index') }}" class="block px-3 py-2.5 rounded-md text-sm font-medium" style="color:#5e5d59">Serviços</a>
                <a href="{{ route('projects.index') }}" class="block px-3 py-2.5 rounded-md text-sm font-medium" style="color:#5e5d59">Portfólio</a>
                <a href="{{ route('blog.index') }}"     class="block px-3 py-2.5 rounded-md text-sm font-medium" style="color:#5e5d59">Blog</a>
                <a href="{{ route('contact') }}"        class="block px-3 py-2.5 rounded-md text-sm font-medium" style="color:#5e5d59">Contato</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main id="main-content" class="flex-grow pt-[4.25rem]">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="mt-24 border-t border-[#faf9f5]/10" style="background-color:#141413">
        <div class="max-w-7xl mx-auto py-14 px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10">
                <div class="col-span-1 md:col-span-2">
                    <a href="{{ route('home') }}" class="flex items-center gap-2 mb-5">
                        <div class="w-7 h-7 rounded-lg flex items-center justify-center bg-clay" style="background-color:#d97757">
                            <svg class="w-4 h-4" fill="none" stroke="#faf9f5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <span class="font-heading font-semibold text-base" style="color:#faf9f5">{{ config('app.name', 'Etude Rapide') }}</span>
                    </a>
                    <p class="text-sm leading-relaxed max-w-sm mb-6" style="color:rgba(250,249,245,0.7)">
                        Construindo experiências digitais modernas, focadas em performance, design premium e inteligência artificial.
                    </p>
                    <div class="flex space-x-4" style="color:rgba(250,249,245,0.5)">
                        <a href="#" aria-label="Twitter" class="hover:text-clay transition-colors"><svg aria-hidden="true" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"/></svg></a>
                        <a href="#" aria-label="GitHub" class="hover:text-clay transition-colors"><svg aria-hidden="true" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd"/></svg></a>
                        <a href="#" aria-label="LinkedIn" class="hover:text-clay transition-colors"><svg aria-hidden="true" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg></a>
                    </div>
                </div>
                
                <div>
                    <h3 class="text-xs font-semibold tracking-widest uppercase mb-5" style="color:rgba(250,249,245,0.5)">Navegação</h3>
                    <ul class="space-y-3">
                        <li><a href="{{ route('home') }}"           class="text-sm transition-colors hover:text-[#faf9f5]" style="color:rgba(250,249,245,0.7)">Início</a></li>
                        <li><a href="{{ route('about') }}"          class="text-sm transition-colors hover:text-[#faf9f5]" style="color:rgba(250,249,245,0.7)">Sobre Nós</a></li>
                        <li><a href="{{ route('services.index') }}" class="text-sm transition-colors hover:text-[#faf9f5]" style="color:rgba(250,249,245,0.7)">Serviços</a></li>
                        <li><a href="{{ route('projects.index') }}" class="text-sm transition-colors hover:text-[#faf9f5]" style="color:rgba(250,249,245,0.7)">Portfólio</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-xs font-semibold tracking-widest uppercase mb-5" style="color:rgba(250,249,245,0.5)">Legal</h3>
                    <ul class="space-y-3">
                        <li><a href="#"                      class="text-sm transition-colors hover:text-[#faf9f5]" style="color:rgba(250,249,245,0.7)">Termos de Uso</a></li>
                        <li><a href="#"                      class="text-sm transition-colors hover:text-[#faf9f5]" style="color:rgba(250,249,245,0.7)">Privacidade</a></li>
                        <li><a href="{{ route('contact') }}" class="text-sm transition-colors hover:text-[#faf9f5]" style="color:rgba(250,249,245,0.7)">Contato</a></li>
                    </ul>
                </div>
            </div>

            <div class="mt-14 pt-8 border-t border-[#faf9f5]/10 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-sm" style="color:rgba(250,249,245,0.5)">
                    &copy; {{ date('Y') }} {{ config('app.name', 'Etude Rapide') }}. Todos os direitos reservados.
                </p>
                <p class="text-sm flex items-center gap-1" style="color:rgba(250,249,245,0.6)">
                    Desenvolvido com <svg class="w-3.5 h-3.5" fill="#d97757" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/></svg> &amp; IA
                </p>
            </div>
        </div>
    </footer>

    @stack('scripts')
    <x-whatsapp-button />
</body>
</html>
