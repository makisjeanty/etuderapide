@extends('layouts.admin_premium')

@section('header', 'Comando Geral')
@section('subtitle', 'Visão geral da sua plataforma Makis Digital.')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
    <!-- Stat Cards -->
    <div class="stat-card glass-panel">
        <div class="flex justify-between items-start mb-4">
            <div class="w-12 h-12 bg-blue-500/20 rounded-xl flex items-center justify-center text-blue-400">
                <i class="fas fa-file-alt text-2xl"></i>
            </div>
            <span class="text-xs font-bold text-blue-400 bg-blue-500/10 px-2 py-1 rounded-full">Conteúdo</span>
        </div>
        <p class="text-slate-400 text-sm font-medium">Total de Posts</p>
        <h3 class="text-3xl font-bold mt-1">{{ $postsCount }}</h3>
    </div>

    <div class="stat-card glass-panel">
        <div class="flex justify-between items-start mb-4">
            <div class="w-12 h-12 bg-amber-500/20 rounded-xl flex items-center justify-center text-amber-400">
                <i class="fas fa-briefcase text-2xl"></i>
            </div>
            <span class="text-xs font-bold text-amber-400 bg-amber-500/10 px-2 py-1 rounded-full">Portfólio</span>
        </div>
        <p class="text-slate-400 text-sm font-medium">Projetos Cadastrados</p>
        <h3 class="text-3xl font-bold mt-1">{{ $projectsCount }}</h3>
    </div>

    <div class="stat-card glass-panel">
        <div class="flex justify-between items-start mb-4">
            <div class="w-12 h-12 bg-emerald-500/20 rounded-xl flex items-center justify-center text-emerald-400">
                <i class="fas fa-envelope-open-text text-2xl"></i>
            </div>
            <span class="text-xs font-bold text-emerald-400 bg-emerald-500/10 px-2 py-1 rounded-full">Novo</span>
        </div>
        <p class="text-slate-400 text-sm font-medium">Leads Totais</p>
        <h3 class="text-3xl font-bold mt-1">{{ $totalLeadsCount }}</h3>
    </div>

    <div class="stat-card glass-panel">
        <div class="flex justify-between items-start mb-4">
            <div class="w-12 h-12 bg-purple-500/20 rounded-xl flex items-center justify-center text-purple-400">
                <i class="fas fa-chart-line text-2xl"></i>
            </div>
            <span class="text-xs font-bold text-purple-400 bg-purple-500/10 px-2 py-1 rounded-full">Top</span>
        </div>
        <p class="text-slate-400 text-sm font-medium">Taxa de Conversão</p>
        <h3 class="text-3xl font-bold mt-1">{{ $business['conversion_rate'] }}%</h3>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Recent Activity -->
    <div class="lg:col-span-2 glass-panel p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold">Leads Recentes</h3>
            <a href="{{ route('admin.leads.index') }}" class="text-amber-500 text-sm hover:underline">Ver Todos</a>
        </div>
        
        <table class="table-glass">
            <thead>
                <tr>
                    <th>Lead</th>
                    <th>Interesse</th>
                    <th>Data</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentLeads as $lead)
                <tr>
                    <td>
                        <div class="flex items-center">
                            <i class="fas fa-circle text-[8px] {{ $lead->status === 'new' ? 'text-emerald-500' : 'text-amber-500' }} mr-3"></i>
                            <span class="font-medium">{{ $lead->name }}</span>
                        </div>
                    </td>
                    <td class="text-slate-300">{{ $lead->service_interest ?? 'Geral' }}</td>
                    <td class="text-slate-400 text-sm">{{ $lead->created_at?->diffForHumans() }}</td>
                    <td>
                        <span class="px-2 py-1 rounded-md text-xs font-bold uppercase {{ $lead->status === 'new' ? 'bg-emerald-500/10 text-emerald-500' : 'bg-amber-500/10 text-amber-400' }}">
                            {{ $lead->status }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center text-slate-400 py-6">Nenhum lead recente encontrado.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Quick Actions -->
    <div class="glass-panel p-6">
        <h3 class="text-xl font-bold mb-6">Ações Rápidas</h3>
        <div class="space-y-4">
            <a href="{{ route('admin.posts.create') }}" class="flex items-center p-4 rounded-xl bg-slate-800/50 border border-slate-700/50 hover:border-amber-500/50 transition-all group">
                <div class="w-10 h-10 rounded-lg bg-blue-500/20 flex items-center justify-center text-blue-400 group-hover:scale-110 transition-transform">
                    <i class="fas fa-plus"></i>
                </div>
                <div class="ml-4">
                    <p class="font-semibold">Criar Novo Post</p>
                    <p class="text-xs text-slate-500">Publicar no blog</p>
                </div>
            </a>

            <a href="{{ route('admin.projects.create') }}" class="flex items-center p-4 rounded-xl bg-slate-800/50 border border-slate-700/50 hover:border-amber-500/50 transition-all group">
                <div class="w-10 h-10 rounded-lg bg-amber-500/20 flex items-center justify-center text-amber-400 group-hover:scale-110 transition-transform">
                    <i class="fas fa-folder-plus"></i>
                </div>
                <div class="ml-4">
                    <p class="font-semibold">Novo Projeto</p>
                    <p class="text-xs text-slate-500">Adicionar portfólio</p>
                </div>
            </a>

            <a href="{{ route('admin.leads.index') }}" class="flex items-center p-4 rounded-xl bg-slate-800/50 border border-slate-700/50 hover:border-emerald-500/50 transition-all group">
                <div class="w-10 h-10 rounded-lg bg-emerald-500/20 flex items-center justify-center text-emerald-400 group-hover:scale-110 transition-transform">
                    <i class="fas fa-inbox"></i>
                </div>
                <div class="ml-4">
                    <p class="font-semibold">Acompanhar Leads</p>
                    <p class="text-xs text-slate-500">{{ $newLeadsCount }} novos aguardando retorno</p>
                </div>
            </a>

            <div class="mt-8 p-4 rounded-xl bg-amber-500/5 border border-amber-500/20">
                <p class="text-sm text-amber-500 font-semibold mb-2">Saúde Operacional</p>
                <p class="text-xs text-slate-400 leading-relaxed">
                    Banco: {{ $systemHealth['database'] ? 'ok' : 'falha' }} |
                    Cache: {{ $systemHealth['cache'] ? 'ok' : 'falha' }} |
                    Storage: {{ $systemHealth['storage'] ? 'ok' : 'falha' }}
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
