<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detalhes do Log') }} #{{ $auditLog->id }}
            </h2>
            <a href="{{ route('admin.audit-logs.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900">&larr; Voltar</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-medium border-b pb-2 mb-4">Informações Gerais</h3>
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-semibold text-gray-500 uppercase">Ação</dt>
                                    <dd class="text-md">{{ $auditLog->action }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-semibold text-gray-500 uppercase">Usuário</dt>
                                    <dd class="text-md">{{ $auditLog->user->name ?? 'Sistema' }} ({{ $auditLog->user->email ?? 'N/A' }})</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-semibold text-gray-500 uppercase">Data/Hora</dt>
                                    <dd class="text-md">{{ $auditLog->created_at->format('d/m/Y H:i:s') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-semibold text-gray-500 uppercase">Endereço IP</dt>
                                    <dd class="text-md">{{ $auditLog->ip_address }}</dd>
                                </div>
                            </dl>
                        </div>

                        <div>
                            <h3 class="text-lg font-medium border-b pb-2 mb-4">Propriedades / Dados</h3>
                            <div class="bg-gray-50 p-4 rounded border">
                                @if($auditLog->properties)
                                    <pre class="text-xs overflow-auto">{{ json_encode($auditLog->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                @else
                                    <p class="text-sm text-gray-500 italic">Nenhuma propriedade adicional registrada.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
