@props([
    'phone' => null,
    'message' => 'Olá, gostaria de saber mais sobre a Makis Digital.'
])

@php
    $phone = preg_replace('/[^0-9]/', '', (string) ($phone ?: config('services.whatsapp.admin_phone', '')));
    $currentUrl = request()->url();
    $customMessage = $message;

    // Lógica para mensagens dinâmicas
    if (isset($service)) {
        $customMessage = "Olá! Vi o serviço '" . $service->name . "' no site e gostaria de saber mais sobre como contratar.";
    } elseif (isset($project)) {
        $customMessage = "Olá! Vi o case de sucesso '" . $project->title . "' no site e gostaria de algo similar para o meu negócio.";
    } elseif (request()->routeIs('blog.show')) {
        $customMessage = "Olá! Acabei de ler o artigo no blog e gostaria de tirar uma dúvida técnica.";
    }

    $whatsappUrl = "https://wa.me/{$phone}?text=" . urlencode($customMessage);
@endphp

@if ($phone !== '')
<div class="fixed bottom-8 right-8 z-50 group">
    <!-- Tooltip / Label -->
    <div class="absolute right-full mr-4 bottom-2 px-4 py-2 bg-white text-slate-900 text-sm font-bold rounded-xl whitespace-nowrap opacity-0 group-hover:opacity-100 translate-x-4 group-hover:translate-x-0 transition-all duration-300 pointer-events-none shadow-xl">
        Falar com Especialista 🚀
    </div>

    <!-- Botão -->
    <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer" 
       class="flex items-center justify-center w-16 h-16 bg-[#25D366] text-white rounded-full shadow-[0_0_20px_rgba(37,211,102,0.4)] hover:shadow-[0_0_30px_rgba(37,211,102,0.6)] hover:scale-110 active:scale-95 transition-all duration-300 relative overflow-hidden">
        
        <!-- Efeito de pulso -->
        <span class="absolute inset-0 rounded-full bg-white/20 animate-ping opacity-75"></span>

        <svg class="w-8 h-8 relative z-10" fill="currentColor" viewBox="0 0 24 24">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.414 0 .018 5.393 0 12.03a11.782 11.782 0 001.574 5.961L0 24l6.135-1.61a11.767 11.767 0 005.912 1.586h.006c6.634 0 12.032-5.396 12.035-12.032a11.761 11.761 0 00-3.476-8.508z"></path>
        </svg>
    </a>
</div>
@endif
