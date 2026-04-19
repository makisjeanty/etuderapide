@php
    /** @var \App\Models\Service|null $service */
    $service = $service ?? null;
@endphp

<div class="space-y-6" x-data="aiAssistant('{{ route('admin.ai.generate') }}', 'name')">
    <div>
        <x-input-label for="name" :value="__('Name')" />
        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $service?->name)" required autofocus />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="slug" :value="__('Slug (optional)')" />
        <x-text-input id="slug" class="block mt-1 w-full font-mono text-sm" type="text" name="slug" :value="old('slug', $service?->slug)" />
        <x-input-error :messages="$errors->get('slug')" class="mt-2" />
    </div>

    <div>
        <div class="flex items-center justify-between">
            <x-input-label for="short_description" :value="__('Short description')" />
            <button type="button" @click="generate('summary', 'short_description')" class="text-xs text-purple-600 hover:text-purple-900 font-medium flex items-center transition-colors">
                <span x-show="!loading['short_description']">🪄 Gerar Resumo</span>
                <span x-show="loading['short_description']" x-cloak>⏳ Processando...</span>
            </button>
        </div>
        <textarea id="short_description" name="short_description" rows="3" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">{{ old('short_description', $service?->short_description) }}</textarea>
        <x-input-error :messages="$errors->get('short_description')" class="mt-2" />
    </div>

    <div>
        <div class="flex items-center justify-between">
            <x-input-label for="full_description" :value="__('Full description')" />
            <button type="button" @click="generate('description', 'full_description')" class="text-xs text-purple-600 hover:text-purple-900 font-medium flex items-center transition-colors">
                <span x-show="!loading['full_description']">🪄 Expandir com IA</span>
                <span x-show="loading['full_description']" x-cloak>⏳ Escrevendo...</span>
            </button>
        </div>
        <textarea id="full_description" name="full_description" rows="8" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">{{ old('full_description', $service?->full_description) }}</textarea>
        <x-input-error :messages="$errors->get('full_description')" class="mt-2" />
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <x-input-label for="price_from" :value="__('Price from')" />
            <x-text-input id="price_from" class="block mt-1 w-full" type="number" step="0.01" min="0" name="price_from" :value="old('price_from', $service?->price_from)" />
            <x-input-error :messages="$errors->get('price_from')" class="mt-2" />
        </div>
        <div>
            <x-input-label for="delivery_time" :value="__('Delivery time')" />
            <x-text-input id="delivery_time" class="block mt-1 w-full" type="text" name="delivery_time" :value="old('delivery_time', $service?->delivery_time)" />
            <x-input-error :messages="$errors->get('delivery_time')" class="mt-2" />
        </div>
    </div>

    <div>
        <x-input-label for="category_id" :value="__('Category')" />
        <select name="category_id" id="category_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
            <option value="">Sem Categoria</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" @selected(old('category_id', $service?->category_id) == $category->id)>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
    </div>

    <div class="flex items-center">
        <input type="hidden" name="is_active" value="0">
        <input id="is_active" type="checkbox" name="is_active" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm"
            {{ old('is_active', $service?->is_active ?? true) ? 'checked' : '' }}>
        <x-input-label for="is_active" class="ms-2" :value="__('Active (visible when public pages exist)')" />
    </div>

    <div>
        <div class="flex items-center justify-between">
            <x-input-label for="call_to_action" :value="__('Call to action')" />
            <button type="button" @click="generate('cta', 'call_to_action')" class="text-xs text-purple-600 hover:text-purple-900 font-medium flex items-center transition-colors">
                <span x-show="!loading['call_to_action']">🪄 Melhorar CTA</span>
                <span x-show="loading['call_to_action']" x-cloak>⏳ Pensando...</span>
            </button>
        </div>
        <x-text-input id="call_to_action" class="block mt-1 w-full" type="text" name="call_to_action" :value="old('call_to_action', $service?->call_to_action)" />
        <x-input-error :messages="$errors->get('call_to_action')" class="mt-2" />
    </div>

    <div>
        <div class="flex items-center justify-between">
            <x-input-label for="seo_title" :value="__('SEO title')" />
            <button type="button" @click="generate('seo_title', 'seo_title')" class="text-xs text-purple-600 hover:text-purple-900 font-medium flex items-center transition-colors">
                <span x-show="!loading['seo_title']">🪄 Otimizar Título</span>
                <span x-show="loading['seo_title']" x-cloak>⏳ Pensando...</span>
            </button>
        </div>
        <x-text-input id="seo_title" class="block mt-1 w-full" type="text" name="seo_title" :value="old('seo_title', $service?->seo_title)" />
        <x-input-error :messages="$errors->get('seo_title')" class="mt-2" />
    </div>

    <div>
        <div class="flex items-center justify-between">
            <x-input-label for="seo_description" :value="__('SEO description')" />
            <button type="button" @click="generate('seo_description', 'seo_description')" class="text-xs text-purple-600 hover:text-purple-900 font-medium flex items-center transition-colors">
                <span x-show="!loading['seo_description']">🪄 Gerar Meta Descrição</span>
                <span x-show="loading['seo_description']" x-cloak>⏳ Pensando...</span>
            </button>
        </div>
        <textarea id="seo_description" name="seo_description" rows="3" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">{{ old('seo_description', $service?->seo_description) }}</textarea>
        <x-input-error :messages="$errors->get('seo_description')" class="mt-2" />
    </div>
</div>

