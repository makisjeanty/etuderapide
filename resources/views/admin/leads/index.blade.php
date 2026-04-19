<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Caixa de Entrada (Leads)') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Status</th>
                                    <th scope="col" class="px-6 py-3">Nome</th>
                                    <th scope="col" class="px-6 py-3">Email</th>
                                    <th scope="col" class="px-6 py-3">Serviço de Interesse</th>
                                    <th scope="col" class="px-6 py-3">Data</th>
                                    <th scope="col" class="px-6 py-3 text-right">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($leads as $lead)
                                    <tr class="bg-white border-b hover:bg-gray-50 {{ $lead->status === 'new' ? 'font-bold text-gray-900 bg-blue-50/50' : '' }}">
                                        <td class="px-6 py-4">
                                            @if($lead->status === 'new')
                                                <span class="px-2 py-1 text-xs rounded bg-red-100 text-red-800">Novo</span>
                                            @elseif($lead->status === 'read')
                                                <span class="px-2 py-1 text-xs rounded bg-blue-100 text-blue-800">Lido</span>
                                            @elseif($lead->status === 'replied')
                                                <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-800">Respondido</span>
                                            @else
                                                <span class="px-2 py-1 text-xs rounded bg-gray-100 text-gray-800">Arquivado</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">{{ $lead->name }}</td>
                                        <td class="px-6 py-4">{{ $lead->email }}</td>
                                        <td class="px-6 py-4">{{ $lead->service_interest ?? '-' }}</td>
                                        <td class="px-6 py-4">{{ $lead->created_at->format('d/m/Y H:i') }}</td>
                                        <td class="px-6 py-4 text-right space-x-2">
                                            <a href="{{ route('admin.leads.show', $lead) }}" class="font-medium text-indigo-600 hover:text-indigo-900">Visualizar</a>
                                            
                                            <form action="{{ route('admin.leads.destroy', $lead) }}" method="POST" class="inline-block" onsubmit="return confirm('Tem certeza que deseja excluir este contato?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="font-medium text-red-600 hover:text-red-900">Excluir</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                            Nenhum lead recebido ainda.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if(method_exists($leads, 'links'))
                        <div class="mt-4">
                            {{ $leads->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
