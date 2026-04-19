@php
    /** @var \App\Models\Post|null $post */
    $post = $post ?? null;
@endphp

<div class="space-y-6" x-data="aiAssistant('{{ route('admin.ai.generate') }}', 'title')">
    <div class="flex justify-between items-end gap-4">
        <div class="flex-1">
            <x-input-label for="title" :value="__('Title')" />
            <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title', $post?->title)" required autofocus />
        </div>
        <x-ai-button action="summary" target="title" class="mb-0.5" @click.prevent="generate('summary', 'title')">
            Sugerir Título
        </x-ai-button>
    </div>
    <x-input-error :messages="$errors->get('title')" class="mt-2" />

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <x-input-label for="slug" :value="__('Slug (optional)')" />
            <x-text-input id="slug" class="block mt-1 w-full font-mono text-sm" type="text" name="slug" :value="old('slug', $post?->slug)" />
            <p class="mt-1 text-sm text-gray-500">{{ __('Leave empty to generate from title.') }}</p>
            <x-input-error :messages="$errors->get('slug')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="category_id" :value="__('Categoria')" />
            <select name="category_id" id="category_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                <option value="">Sem Categoria</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @selected(old('category_id', $post?->category_id) == $category->id)>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
        </div>
    </div>

    @if(isset($tags) && $tags->count())
    <div>
        <x-input-label :value="__('Tags')" />
        <div class="mt-2 flex flex-wrap gap-3">
            @foreach($tags as $tag)
                <label class="inline-flex items-center">
                    <input type="checkbox" name="tags[]" value="{{ $tag->id }}" class="rounded border-gray-300 text-indigo-600 shadow-sm"
                        {{ in_array($tag->id, old('tags', isset($post) ? $post->tags->pluck('id')->toArray() : [])) ? 'checked' : '' }}>
                    <span class="ms-2 text-sm text-gray-700">{{ $tag->name }}</span>
                </label>
            @endforeach
        </div>
        <x-input-error :messages="$errors->get('tags')" class="mt-2" />
    </div>
    @endif

    <div class="space-y-1">
        <div class="flex justify-between items-center">
            <x-input-label for="body" :value="__('Body')" />
            <x-ai-button action="description" target="body" @click.prevent="generate('description', 'body', 'append')">
                Completar com IA
            </x-ai-button>
        </div>
        <textarea id="body" name="body" rows="12" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>{{ old('body', $post?->body) }}</textarea>
        <x-input-error :messages="$errors->get('body')" class="mt-2" />
    </div>

    <div class="flex items-center">
        <input id="is_published" type="checkbox" name="is_published" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm"
            {{ old('is_published', $post?->is_published) ? 'checked' : '' }}>
        <x-input-label for="is_published" class="ms-2" :value="__('Published')" />
    </div>

    <div>
        <x-input-label for="published_at" :value="__('Publish at (optional)')" />
        <x-text-input id="published_at" class="block mt-1 w-full" type="datetime-local" name="published_at"
            :value="old('published_at', optional($post?->published_at)?->format('Y-m-d\TH:i'))" />
        <x-input-error :messages="$errors->get('published_at')" class="mt-2" />
    </div>

    <div class="border-t pt-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Media & SEO') }}</h3>
        
        <div class="space-y-6">
            <x-image-uploader name="featured_image" :value="old('featured_image', $post?->featured_image)" label="Imagem de Destaque" />

            <div>
                <div class="flex justify-between items-center">
                    <x-input-label for="seo_title" :value="__('SEO Title')" />
                    <x-ai-button action="seo_title" target="seo_title" @click.prevent="generate('seo_title', 'seo_title')">Otimizar Título</x-ai-button>
                </div>
                <x-text-input id="seo_title" class="block mt-1 w-full" type="text" name="seo_title" :value="old('seo_title', $post?->seo_title)" />
                <x-input-error :messages="$errors->get('seo_title')" class="mt-2" />
            </div>

            <div>
                <div class="flex justify-between items-center">
                    <x-input-label for="seo_description" :value="__('SEO Description')" />
                    <x-ai-button action="seo_description" target="seo_description" @click.prevent="generate('seo_description', 'seo_description')">Gerar Meta Descrição</x-ai-button>
                </div>
                <textarea id="seo_description" name="seo_description" rows="3" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('seo_description', $post?->seo_description) }}</textarea>
                <x-input-error :messages="$errors->get('seo_description')" class="mt-2" />
            </div>
        </div>
    </div>
</div>
