<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Makis Digital') }} - Admin</title>

    <!-- Fonts & Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom Premium Styles -->
    <link rel="stylesheet" href="{{ asset('css/admin_premium.css') }}">
    
    <!-- Hamburger Toggle -->
    <button id="sidebarToggle" class="fixed top-4 left-4 z-50 w-12 h-12 glass-panel flex items-center justify-center text-slate-400 hover:text-amber-500 transition-colors hidden md:flex lg:hidden">
        <i class="fas fa-bars text-xl"></i>
    </button>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar glass-panel">
        <div class="flex items-center space-x-3 mb-10 px-4">
            <div class="w-10 h-10 bg-amber-500 rounded-xl flex items-center justify-center shadow-lg shadow-amber-500/20">
                <i class="fas fa-bolt text-slate-900 text-xl"></i>
            </div>
            <span class="text-xl font-bold tracking-tight">Makis <span class="text-amber-500">Digital</span></span>
        </div>

        <nav class="space-y-1">
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-chart-pie w-6"></i>
                <span>Dashboard</span>
            </a>

            <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider mt-6 mb-2 px-4">Conteúdo</div>
            
            <a href="{{ route('admin.posts.index') }}" class="sidebar-link {{ request()->routeIs('admin.posts.*') ? 'active' : '' }}">
                <i class="fas fa-file-alt w-6"></i>
                <span>Blog Posts</span>
            </a>
            <a href="{{ route('admin.projects.index') }}" class="sidebar-link {{ request()->routeIs('admin.projects.*') ? 'active' : '' }}">
                <i class="fas fa-briefcase w-6"></i>
                <span>Projetos</span>
            </a>
            <a href="{{ route('admin.services.index') }}" class="sidebar-link {{ request()->routeIs('admin.services.*') ? 'active' : '' }}">
                <i class="fas fa-concierge-bell w-6"></i>
                <span>Serviços</span>
            </a>

            <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider mt-6 mb-2 px-4">Gestão</div>
            
            <a href="{{ route('admin.leads.index') }}" class="sidebar-link {{ request()->routeIs('admin.leads.*') ? 'active' : '' }}">
                <i class="fas fa-envelope-open-text w-6"></i>
                <span>Leads</span>
            </a>
            <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="fas fa-users w-6"></i>
                <span>Equipe</span>
            </a>
            <a href="{{ route('admin.audit-logs.index') }}" class="sidebar-link {{ request()->routeIs('admin.audit-logs.*') ? 'active' : '' }}">
                <i class="fas fa-shield-alt w-6"></i>
                <span>Auditoria</span>
            </a>
        </nav>

        <div class="absolute bottom-6 left-6 right-6">
            <div class="glass-panel p-4 flex items-center space-x-3 border-slate-700/50">
                <div class="w-10 h-10 rounded-full bg-slate-700 overflow-hidden">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=f59e0b&color=020617" alt="">
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-slate-400 truncate">Administrador</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-slate-400 hover:text-red-400 transition-colors">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Bar -->
        <header class="flex justify-between items-center mb-10">
            <div>
                <h1 class="text-3xl font-bold">@yield('header', 'Painel')</h1>
                <p class="text-slate-400">@yield('subtitle', 'Bem-vindo ao comando do Makis Digital.')</p>
            </div>
            
            <div class="flex items-center space-x-4">
                <div class="glass-panel px-4 py-2 flex items-center space-x-3">
                    <i class="fas fa-search text-slate-500"></i>
                    <input type="text" placeholder="Buscar..." class="bg-transparent border-none focus:ring-0 text-sm w-48 text-slate-200">
                </div>
                <button class="w-12 h-12 glass-panel flex items-center justify-center text-slate-400 hover:text-amber-500 transition-colors">
                    <i class="fas fa-bell"></i>
                </button>
                <a href="{{ url('/') }}" target="_blank" class="premium-btn">
                    Ver Site <i class="fas fa-external-link-alt ml-2"></i>
                </a>
            </div>
        </header>

        @if (session('success'))
            <div class="glass-panel p-4 mb-6 border-emerald-500/30 bg-emerald-500/10 text-emerald-400 flex items-center">
                <i class="fas fa-check-circle mr-3"></i>
                {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </main>

    <script>
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            document.querySelector('.sidebar')?.classList.toggle('open');
        });
    </script>
</body>
</html>
