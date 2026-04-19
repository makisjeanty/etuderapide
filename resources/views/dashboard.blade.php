<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Geral') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Cards de Estatísticas -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                
                <!-- Leads -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-indigo-500">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-1">Leads Não Lidos</div>
                        <div class="text-3xl font-bold text-gray-900">{{ $stats['unread_leads'] }}</div>
                    </div>
                </div>
                
                <!-- Projetos -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-purple-500">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-1">Projetos</div>
                        <div class="text-3xl font-bold text-gray-900">{{ $stats['projects'] }}</div>
                    </div>
                </div>

                <!-- Serviços -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-blue-500">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-1">Serviços</div>
                        <div class="text-3xl font-bold text-gray-900">{{ $stats['services'] }}</div>
                    </div>
                </div>

                <!-- Artigos -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-emerald-500">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-1">Artigos no Blog</div>
                        <div class="text-3xl font-bold text-gray-900">{{ $stats['posts'] }}</div>
                    </div>
                </div>
            </div>

            <!-- Últimos Leads -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-900">Últimos Contatos Recebidos</h3>
                    <a href="{{ route('admin.leads.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900 font-medium">Ver todos &rarr;</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                                <th class="p-4 border-b font-semibold">Nome</th>
                                <th class="p-4 border-b font-semibold">Email</th>
                                <th class="p-4 border-b font-semibold">Status</th>
                                <th class="p-4 border-b font-semibold">Data</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($stats['recent_leads'] as $lead)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="p-4 font-medium text-gray-900">
                                        <a href="{{ route('admin.leads.show', $lead) }}" class="hover:text-indigo-600">{{ $lead->name }}</a>
                                    </td>
                                    <td class="p-4 text-gray-500">{{ $lead->email }}</td>
                                    <td class="p-4">
                                        @if($lead->status === 'new')
                                            <span class="px-2 py-1 bg-red-100 text-red-800 text-xs font-semibold rounded-full">Novo</span>
                                        @elseif($lead->status === 'read')
                                            <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">Lido</span>
                                        @else
                                            <span class="px-2 py-1 bg-gray-100 text-gray-800 text-xs font-semibold rounded-full">Respondido</span>
                                        @endif
                                    </td>
                                    <td class="p-4 text-gray-500 text-sm">{{ $lead->created_at->diffForHumans() }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-8 text-center text-gray-500">Nenhum contato recebido ainda.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
