<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Mensagem de: ') }} {{ $lead->name }}
            </h2>
            <a href="{{ route('admin.leads.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                &larr; Voltar para Caixa de Entrada
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-1">Detalhes do Contato</h3>
                            <p class="text-lg font-bold text-gray-900">{{ $lead->name }}</p>
                            <p class="text-gray-600">
                                <a href="mailto:{{ $lead->email }}" class="text-indigo-600 hover:text-indigo-900 flex items-center mt-1">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                    {{ $lead->email }}
                                </a>
                            </p>
                            @if($lead->phone)
                                <p class="text-gray-600 mt-2 flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                    {{ $lead->phone }}
                                </p>
                            @endif
                        </div>
                        <div class="md:text-right">
                            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-1">Informações</h3>
                            <p class="text-gray-600">Recebido em: {{ $lead->created_at->format('d/m/Y às H:i') }} ({{ $lead->created_at->diffForHumans() }})</p>
                            @if($lead->service_interest)
                                <p class="mt-2 inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">
                                    Interesse: {{ $lead->service_interest }}
                                </p>
                            @endif
                        </div>
                    </div>

                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-4">Mensagem</h3>
                        <div class="bg-gray-50 rounded-lg p-6 text-gray-800 whitespace-pre-wrap font-serif text-lg leading-relaxed shadow-inner">
                            {{ $lead->message }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gestão Comercial Avançada -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Gestão Comercial (CRM)
                    </h3>
                    
                    <form action="{{ route('admin.leads.status', $lead) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PATCH')
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Status -->
                            <div>
                                <x-input-label for="status" value="Status do Lead" />
                                <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="new" {{ $lead->status === 'new' ? 'selected' : '' }}>Novo</option>
                                    <option value="read" {{ $lead->status === 'read' ? 'selected' : '' }}>Lido</option>
                                    <option value="replied" {{ $lead->status === 'replied' ? 'selected' : '' }}>Em Negociação</option>
                                    <option value="archived" {{ $lead->status === 'archived' ? 'selected' : '' }}>Arquivado / Fechado</option>
                                </select>
                            </div>

                            <!-- Valor Cotado -->
                            <div>
                                <x-input-label for="quoted_value" value="Valor do Orçamento (R$)" />
                                <x-text-input id="quoted_value" name="quoted_value" type="number" step="0.01" class="mt-1 block w-full" :value="$lead->quoted_value" placeholder="Ex: 2500.00" />
                            </div>

                            <!-- Link de Pagamento -->
                            <div>
                                <x-input-label for="payment_link" value="Link de Pagamento (Stripe/Mercado Pago)" />
                                <x-text-input id="payment_link" name="payment_link" type="url" class="mt-1 block w-full text-sm" :value="$lead->payment_link" placeholder="https://buy.stripe.com/..." />
                            </div>
                        </div>

                        <!-- Notas Internas -->
                        <div>
                            <x-input-label for="internal_notes" value="Notas Internas (Histórico da Negociação)" />
                            <textarea id="internal_notes" name="internal_notes" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Ex: Conversamos via WhatsApp, cliente pediu 10% de desconto...">{{ $lead->internal_notes }}</textarea>
                            <p class="mt-2 text-xs text-gray-500">Estas notas são privadas e só aparecem aqui para você.</p>
                        </div>

                        <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                            <div class="flex items-center gap-4">
                                <button type="submit" class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:border-indigo-700 focus:ring ring-indigo-300 transition ease-in-out duration-150 shadow-lg shadow-indigo-500/20">
                                    Salvar Alterações
                                </button>
                                
                                <a href="{{ $whatsappLink }}" target="_blank" class="inline-flex items-center px-6 py-3 bg-green-600 border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-widest hover:bg-green-500 transition ease-in-out duration-150 shadow-lg shadow-green-500/20">
                                    Falar via WhatsApp 📱
                                </a>
                                
                                <a href="mailto:{{ $lead->email }}?subject=Proposta Makis Digital - {{ $lead->service_interest }}" class="text-sm font-medium text-gray-600 hover:text-indigo-600 underline">
                                    Enviar E-mail com Proposta
                                </a>
                            </div>

                            @if($lead->payment_link)
                                <div class="flex items-center gap-2 px-4 py-2 bg-green-50 text-green-700 rounded-lg border border-green-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <span class="text-xs font-bold uppercase">Link Pronto</span>
                                </div>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
