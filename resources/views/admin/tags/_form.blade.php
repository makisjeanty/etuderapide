@php
    $tag = $tag ?? null;
@endphp

<div class="space-y-6">
    <div>
        <x-input-label for="name" :value="__('Nome da Tag')" />
        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $tag?->name)" required autofocus />
        <x-input-error class="mt-2" :messages="$errors->get('name')" />
    </div>

    <div>
        <x-input-label for="slug" :value="__('Slug (URL)')" />
        <x-text-input id="slug" name="slug" type="text" class="mt-1 block w-full font-mono text-sm" :value="old('slug', $tag?->slug)" placeholder="deixe em branco para gerar do nome" />
        <x-input-error class="mt-2" :messages="$errors->get('slug')" />
    </div>

    <div>
        <x-input-label for="description" :value="__('Descrição (Opcional)')" />
        <textarea id="description" name="description" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('description', $tag?->description) }}</textarea>
        <x-input-error class="mt-2" :messages="$errors->get('description')" />
    </div>
</div>
