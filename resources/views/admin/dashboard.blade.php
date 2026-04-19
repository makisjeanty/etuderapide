<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Painel de Controle') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Cards de Estatísticas -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <!-- Valor em Negociação -->
                <div class="bg-indigo-600 rounded-2xl p-6 text-white shadow-lg shadow-indigo-500/30">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-white/20 rounded-xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <span class="text-xs font-bold uppercase tracking-wider opacity-80">Em Negociação</span>
                    </div>
                    <div class="text-3xl font-bold mb-1">R$ {{ number_format($totalPipelineValue, 2, ',', '.') }}</div>
                    <p class="text-indigo-100 text-sm">Volume total do seu funil</p>
                </div>

                <!-- Novos Leads -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-red-50 text-red-600 rounded-xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Novos Leads</span>
                    </div>
                    <div class="text-3xl font-bold text-gray-900 mb-1">{{ $newLeadsCount }}</div>
                    <p class="text-gray-500 text-sm">Aguardando atendimento</p>
                </div>

                <!-- Projetos Ativos -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-7.714 2.143L11 21l-2.286-6.857L1 12l7.714-2.143L11 3z"></path></svg>
                        </div>
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Cases</span>
                    </div>
                    <div class="text-3xl font-bold text-gray-900 mb-1">{{ $projectsCount }}</div>
                    <p class="text-gray-500 text-sm">No seu portfólio</p>
                </div>

                <!-- Posts no Blog -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-green-50 text-green-600 rounded-xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v12a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Artigos</span>
                    </div>
                    <div class="text-3xl font-bold text-gray-900 mb-1">{{ $postsCount }}</div>
                    <p class="text-gray-500 text-sm">Publicados</p>
                </div>
            </div>

            <!-- Gráficos de Performance -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Gráfico de Evolução de Leads -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <h3 class="font-bold text-gray-900 mb-6">Evolução de Leads (30 dias)</h3>
                    <div style="height: 300px;">
                        <canvas id="leadsHistoryChart"></canvas>
                    </div>
                </div>

                <!-- Gráfico de Status e Interesses -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <h3 class="font-bold text-gray-900 mb-6">Distribuição por Interesse</h3>
                    <div style="height: 300px;">
                        <canvas id="leadsInterestChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Últimos Leads -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                            <h3 class="font-bold text-gray-900">Leads Recentes</h3>
                            <a href="{{ route('admin.leads.index') }}" class="text-sm text-indigo-600 hover:underline">Ver todos</a>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm">
                                <thead>
                                    <tr class="bg-gray-50 text-gray-500 uppercase text-xs">
                                        <th class="px-6 py-3 font-bold">Cliente</th>
                                        <th class="px-6 py-3 font-bold">Serviço</th>
                                        <th class="px-6 py-3 font-bold">Status</th>
                                        <th class="px-6 py-3 font-bold">Valor</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @forelse($recentLeads as $lead)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4 font-medium text-gray-900">
                                                <a href="{{ route('admin.leads.show', $lead) }}" class="hover:text-indigo-600">
                                                    {{ $lead->name }}
                                                </a>
                                            </td>
                                            <td class="px-6 py-4 text-gray-500">{{ $lead->service_interest }}</td>
                                            <td class="px-6 py-4">
                                                <span class="px-2 py-1 rounded text-xs font-bold
                                                    @if($lead->status === 'new') bg-red-100 text-red-700
                                                    @elseif($lead->status === 'replied') bg-blue-100 text-blue-700
                                                    @else bg-gray-100 text-gray-700 @endif">
                                                    {{ strtoupper($lead->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 font-bold text-gray-900">
                                                R$ {{ number_format($lead->quoted_value, 2, ',', '.') }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-12 text-center text-gray-500">Nenhum lead recebido ainda.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Atalhos Rápidos -->
                <div>
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="font-bold text-gray-900 mb-6">Acesso Rápido</h3>
                        <div class="space-y-3">
                            <a href="{{ route('admin.posts.create') }}" class="flex items-center p-4 bg-gray-50 rounded-xl hover:bg-indigo-50 hover:text-indigo-700 transition-all group">
                                <div class="p-2 bg-white rounded-lg shadow-sm mr-4 group-hover:bg-indigo-600 group-hover:text-white transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </div>
                                <span class="font-medium">Criar Post com IA</span>
                            </a>
                            <a href="{{ route('admin.projects.create') }}" class="flex items-center p-4 bg-gray-50 rounded-xl hover:bg-indigo-50 hover:text-indigo-700 transition-all group">
                                <div class="p-2 bg-white rounded-lg shadow-sm mr-4 group-hover:bg-indigo-600 group-hover:text-white transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                </div>
                                <span class="font-medium">Adicionar Case</span>
                            </a>
                            <a href="{{ route('admin.categories.index') }}" class="flex items-center p-4 bg-gray-50 rounded-xl hover:bg-indigo-50 hover:text-indigo-700 transition-all group">
                                <div class="p-2 bg-white rounded-lg shadow-sm mr-4 group-hover:bg-indigo-600 group-hover:text-white transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                                </div>
                                <span class="font-medium">Categorias</span>
                            </a>
                            <a href="{{ route('admin.audit-logs.index') }}" class="flex items-center p-4 bg-gray-50 rounded-xl hover:bg-indigo-50 hover:text-indigo-700 transition-all group">
                                <div class="p-2 bg-white rounded-lg shadow-sm mr-4 group-hover:bg-indigo-600 group-hover:text-white transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                                <span class="font-medium">Logs do Sistema</span>
                            </a>
                        </div>
                    </div>

                    <!-- Business Metrics -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 mt-8">
                        <div class="bg-slate-900/50 border border-slate-800 rounded-2xl p-6">
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-slate-400 text-sm font-medium">Total de Leads</span>
                                <div class="p-2 bg-indigo-500/10 rounded-lg text-indigo-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                </div>
                            </div>
                            <div class="text-3xl font-bold text-white">{{ $business['total_leads'] }}</div>
                            <div class="mt-2 text-xs text-indigo-400 font-medium">+{{ $business['pending_leads'] }} novos pendentes</div>
                        </div>

                        <div class="bg-slate-900/50 border border-slate-800 rounded-2xl p-6">
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-slate-400 text-sm font-medium">Taxa de Conversão</span>
                                <div class="p-2 bg-emerald-500/10 rounded-lg text-emerald-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                                </div>
                            </div>
                            <div class="text-3xl font-bold text-white">{{ $business['conversion_rate'] }}%</div>
                            <div class="mt-2 text-xs text-slate-500 font-medium">Estimado sobre visitas</div>
                        </div>

                        <div class="bg-slate-900/50 border border-slate-800 rounded-2xl p-6">
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-slate-400 text-sm font-medium">Status do Sistema</span>
                                <div class="flex gap-2">
                                    <div class="w-2 h-2 rounded-full {{ $health['database'] ? 'bg-emerald-500 animate-pulse' : 'bg-red-500' }}" title="Database"></div>
                                    <div class="w-2 h-2 rounded-full {{ $health['cache'] ? 'bg-emerald-500' : 'bg-red-500' }}" title="Cache"></div>
                                    <div class="w-2 h-2 rounded-full {{ $health['storage'] ? 'bg-emerald-500' : 'bg-red-500' }}" title="Storage"></div>
                                </div>
                            </div>
                            <div class="text-lg font-bold text-white">SAUDÁVEL</div>
                            <div class="mt-2 text-xs text-slate-500 font-medium">Monitoramento ativo 24/7</div>
                        </div>
                    </div>

                    <!-- Saúde do Sistema -->
                    <div class="mt-8 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="font-bold text-gray-900 mb-4 text-sm uppercase tracking-wider">Status do Sistema</h3>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500">Banco de Dados</span>
                                <span class="flex h-2 w-2 rounded-full {{ $systemHealth['database'] ? 'bg-green-500' : 'bg-red-500' }}"></span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500">Cache & Sessão</span>
                                <span class="flex h-2 w-2 rounded-full {{ $systemHealth['cache'] ? 'bg-green-500' : 'bg-red-500' }}"></span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500">Storage (Escrita)</span>
                                <span class="flex h-2 w-2 rounded-full {{ $systemHealth['storage'] ? 'bg-green-500' : 'bg-red-500' }}"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
