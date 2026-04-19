<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>@yield('title', config('app.name', 'Makis Digital'))</title>
    
    <!-- Meta SEO -->
    <meta name="description" content="@yield('meta_description', 'Plataforma premium para profissionais.')">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|outfit:400,500,700" rel="stylesheet" />

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, h4, h5, h6, .font-heading { font-family: 'Outfit', sans-serif; }
        
        .glass-panel {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        
        .glass-nav {
            background: rgba(15, 23, 42, 0.7);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }
    </style>
</head>
<body class="antialiased min-h-screen flex flex-col bg-slate-950 text-slate-200 selection:bg-indigo-500 selection:text-white">

    <!-- Decorative Background Effects -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none -z-10">
        <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] rounded-full bg-indigo-600/10 blur-[120px]"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] rounded-full bg-purple-600/10 blur-[120px]"></div>
    </div>

    <!-- Navigation -->
    <nav class="fixed top-0 w-full z-50 glass-nav transition-all duration-300" x-data="{ mobileMenuOpen: false, scrolled: false }" @scroll.window="scrolled = (window.pageYOffset > 20)">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-500 to-purple-500 flex items-center justify-center shadow-lg shadow-indigo-500/30 group-hover:shadow-indigo-500/50 transition-all">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <span class="font-heading font-bold text-xl tracking-tight text-white group-hover:text-indigo-300 transition-colors">
                            {{ config('app.name', 'Makis Digital') }}
                        </span>
                    </a>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-1">
                    <a href="{{ route('home') }}" class="px-4 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('home') ? 'text-indigo-400 bg-white/5' : 'text-slate-300 hover:text-white hover:bg-white/5' }} transition-all">Início</a>
                    <a href="{{ route('about') }}" class="px-4 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('about') ? 'text-indigo-400 bg-white/5' : 'text-slate-300 hover:text-white hover:bg-white/5' }} transition-all">Sobre</a>
                    <a href="{{ route('services.index') }}" class="px-4 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('services.*') ? 'text-indigo-400 bg-white/5' : 'text-slate-300 hover:text-white hover:bg-white/5' }} transition-all">Serviços</a>
                    <a href="{{ route('projects.index') }}" class="px-4 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('projects.*') ? 'text-indigo-400 bg-white/5' : 'text-slate-300 hover:text-white hover:bg-white/5' }} transition-all">Portfólio</a>
                    <a href="{{ route('blog.index') }}" class="px-4 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('blog.*') ? 'text-indigo-400 bg-white/5' : 'text-slate-300 hover:text-white hover:bg-white/5' }} transition-all">Blog</a>
                    <a href="{{ route('contact') }}" class="px-4 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('contact') ? 'text-indigo-400 bg-white/5' : 'text-slate-300 hover:text-white hover:bg-white/5' }} transition-all">Contato</a>
                </div>

                <!-- CTA -->
                <div class="hidden md:flex items-center space-x-4">
                    <a href="{{ route('contact') }}" class="inline-flex items-center justify-center px-5 py-2.5 border border-transparent text-sm font-medium rounded-xl text-white bg-indigo-600 hover:bg-indigo-500 shadow-[0_0_15px_rgba(79,70,229,0.3)] hover:shadow-[0_0_20px_rgba(79,70,229,0.5)] transition-all">
                        Falar Conosco
                    </a>
                </div>

                <!-- Mobile menu button -->
                <div class="flex md:hidden">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="text-slate-300 hover:text-white p-2">
                        <svg class="h-6 w-6" x-show="!mobileMenuOpen" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg class="h-6 w-6" x-show="mobileMenuOpen" style="display: none;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileMenuOpen" style="display: none;" class="md:hidden glass-panel border-t-0 border-x-0" x-transition>
            <div class="px-4 pt-2 pb-6 space-y-1">
                <a href="{{ route('home') }}" class="block px-3 py-3 rounded-md text-base font-medium {{ request()->routeIs('home') ? 'text-indigo-400 bg-white/5' : 'text-slate-300 hover:text-white hover:bg-white/5' }}">Início</a>
                <a href="{{ route('about') }}" class="block px-3 py-3 rounded-md text-base font-medium {{ request()->routeIs('about') ? 'text-indigo-400 bg-white/5' : 'text-slate-300 hover:text-white hover:bg-white/5' }}">Sobre</a>
                <a href="{{ route('services.index') }}" class="block px-3 py-3 rounded-md text-base font-medium {{ request()->routeIs('services.*') ? 'text-indigo-400 bg-white/5' : 'text-slate-300 hover:text-white hover:bg-white/5' }}">Serviços</a>
                <a href="{{ route('projects.index') }}" class="block px-3 py-3 rounded-md text-base font-medium {{ request()->routeIs('projects.*') ? 'text-indigo-400 bg-white/5' : 'text-slate-300 hover:text-white hover:bg-white/5' }}">Portfólio</a>
                <a href="{{ route('blog.index') }}" class="block px-3 py-3 rounded-md text-base font-medium {{ request()->routeIs('blog.*') ? 'text-indigo-400 bg-white/5' : 'text-slate-300 hover:text-white hover:bg-white/5' }}">Blog</a>
                <a href="{{ route('contact') }}" class="block px-3 py-3 rounded-md text-base font-medium {{ request()->routeIs('contact') ? 'text-indigo-400 bg-white/5' : 'text-slate-300 hover:text-white hover:bg-white/5' }}">Contato</a>
                
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow pt-20">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="mt-24 border-t border-slate-800/60 bg-slate-950/80 backdrop-blur-md">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="col-span-1 md:col-span-2">
                    <a href="{{ route('home') }}" class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-tr from-indigo-500 to-purple-500 flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <span class="font-heading font-bold text-lg text-white">
                            {{ config('app.name', 'Makis Digital') }}
                        </span>
                    </a>
                    <p class="text-slate-400 text-sm max-w-sm mb-6">
                        Construindo experiências digitais modernas, focadas em performance, design premium e inteligência artificial.
                    </p>
                    <div class="flex space-x-4 text-slate-400">
                        <a href="#" class="hover:text-indigo-400 transition-colors">
                            <span class="sr-only">Twitter</span>
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"/></svg>
                        </a>
                        <a href="#" class="hover:text-indigo-400 transition-colors">
                            <span class="sr-only">GitHub</span>
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd"/></svg>
                        </a>
                        <a href="#" class="hover:text-indigo-400 transition-colors">
                            <span class="sr-only">LinkedIn</span>
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                        </a>
                    </div>
                </div>
                
                <div>
                    <h3 class="text-sm font-semibold text-white tracking-wider uppercase mb-4">Navegação</h3>
                    <ul class="space-y-3">
                        <li><a href="{{ route('home') }}" class="text-sm text-slate-400 hover:text-white transition-colors">Início</a></li>
                        <li><a href="{{ route('about') }}" class="text-sm text-slate-400 hover:text-white transition-colors">Sobre Nós</a></li>
                        <li><a href="{{ route('services.index') }}" class="text-sm text-slate-400 hover:text-white transition-colors">Serviços</a></li>
                        <li><a href="{{ route('projects.index') }}" class="text-sm text-slate-400 hover:text-white transition-colors">Portfólio</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-sm font-semibold text-white tracking-wider uppercase mb-4">Legal</h3>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-sm text-slate-400 hover:text-white transition-colors">Termos de Uso</a></li>
                        <li><a href="#" class="text-sm text-slate-400 hover:text-white transition-colors">Privacidade</a></li>
                        <li><a href="{{ route('contact') }}" class="text-sm text-slate-400 hover:text-white transition-colors">Contato</a></li>
                    </ul>
                </div>
            </div>
            <div class="mt-12 pt-8 border-t border-slate-800/60 flex flex-col md:flex-row justify-between items-center">
                <p class="text-sm text-slate-500">
                    &copy; {{ date('Y') }} {{ config('app.name', 'Makis Digital') }}. Todos os direitos reservados.
                </p>
                <p class="text-sm text-slate-600 mt-4 md:mt-0 flex items-center">
                    Desenvolvido com <svg class="w-4 h-4 mx-1 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path></svg> &amp; IA
                </p>
            </div>
        </div>
    </footer>

    @stack('scripts')
    <x-whatsapp-button />
</body>
</html>
