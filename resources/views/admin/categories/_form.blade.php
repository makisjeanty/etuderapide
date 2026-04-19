@php
    $category = $category ?? null;
@endphp

<div class="space-y-6">
    <div>
        <x-input-label for="name" :value="__('Nome da Categoria')" />
        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $category?->name)" required autofocus />
        <x-input-error class="mt-2" :messages="$errors->get('name')" />
    </div>

    <div>
        <x-input-label for="slug" :value="__('Slug (URL)')" />
        <x-text-input id="slug" name="slug" type="text" class="mt-1 block w-full font-mono text-sm" :value="old('slug', $category?->slug)" placeholder="deixe em branco para gerar do nome" />
        <x-input-error class="mt-2" :messages="$errors->get('slug')" />
    </div>

    <div>
        <x-input-label for="type" :value="__('Tipo / Área')" />
        <select name="type" id="type" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
            <option value="general" {{ old('type', $category?->type) == 'general' ? 'selected' : '' }}>Geral</option>
            <option value="post" {{ old('type', $category?->type) == 'post' ? 'selected' : '' }}>Blog (Posts)</option>
            <option value="project" {{ old('type', $category?->type) == 'project' ? 'selected' : '' }}>Projetos</option>
            <option value="service" {{ old('type', $category?->type) == 'service' ? 'selected' : '' }}>Serviços</option>
        </select>
        <x-input-error class="mt-2" :messages="$errors->get('type')" />
    </div>

    <div>
        <x-input-label for="description" :value="__('Descrição (Opcional)')" />
        <textarea id="description" name="description" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('description', $category?->description) }}</textarea>
        <x-input-error class="mt-2" :messages="$errors->get('description')" />
    </div>
</div>
