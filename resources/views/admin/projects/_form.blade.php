@php
    use App\Enums\ProjectStatus;
    /** @var \App\Models\Project|null $project */
    $project = $project ?? null;
    $techLines = old('tech_stack_lines', $project?->tech_stack ? implode("\n", $project->tech_stack) : '');
@endphp

<div class="space-y-6" x-data="aiAssistant('{{ route('admin.ai.generate') }}', 'title')">
    <div>
        <x-input-label for="title" :value="__('Title')" />
        <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title', $project?->title)" required autofocus />
        <x-input-error :messages="$errors->get('title')" class="mt-2" />
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <x-input-label for="slug" :value="__('Slug (optional)')" />
            <x-text-input id="slug" class="block mt-1 w-full font-mono text-sm" type="text" name="slug" :value="old('slug', $project?->slug)" />
            <x-input-error :messages="$errors->get('slug')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="category_id" :value="__('Categoria')" />
            <select name="category_id" id="category_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                <option value="">Sem Categoria</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @selected(old('category_id', $project?->category_id) == $category->id)>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
        </div>
    </div>

    <div>
        <div class="flex items-center justify-between">
            <x-input-label for="summary" :value="__('Summary')" />
            <button type="button" @click="generate('summary', 'summary')" class="text-xs text-purple-600 hover:text-purple-900 font-medium flex items-center transition-colors">
                <span x-show="!loading['summary']">🪄 Gerar Resumo</span>
                <span x-show="loading['summary']" x-cloak>⏳ Processando...</span>
            </button>
        </div>
        <textarea id="summary" name="summary" rows="3" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">{{ old('summary', $project?->summary) }}</textarea>
        <x-input-error :messages="$errors->get('summary')" class="mt-2" />
    </div>

    <div>
        <div class="flex items-center justify-between">
            <x-input-label for="description" :value="__('Description')" />
            <button type="button" @click="generate('description', 'description')" class="text-xs text-purple-600 hover:text-purple-900 font-medium flex items-center transition-colors">
                <span x-show="!loading['description']">🪄 Expandir com IA</span>
                <span x-show="loading['description']" x-cloak>⏳ Escrevendo...</span>
            </button>
        </div>
        <textarea id="description" name="description" rows="8" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">{{ old('description', $project?->description) }}</textarea>
        <x-input-error :messages="$errors->get('description')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="status" :value="__('Status')" />
        <select id="status" name="status" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
            @foreach (ProjectStatus::cases() as $case)
                <option value="{{ $case->value }}" @selected(old('status', $project?->status?->value ?? ProjectStatus::Draft->value) === $case->value)>
                    {{ __($case->name) }}
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('status')" class="mt-2" />
    </div>

    <div>
        <x-image-uploader name="featured_image" :value="old('featured_image', $project?->featured_image)" label="Imagem de Capa do Projeto" />
    </div>

    <div>
        <x-input-label for="tech_stack_lines" :value="__('Tech stack (one per line)')" />
        <textarea id="tech_stack_lines" name="tech_stack_lines" rows="5" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm font-mono text-sm">{{ $techLines }}</textarea>
        <x-input-error :messages="$errors->get('tech_stack_lines')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="repository_url" :value="__('Repository URL')" />
        <x-text-input id="repository_url" class="block mt-1 w-full" type="url" name="repository_url" :value="old('repository_url', $project?->repository_url)" />
        <x-input-error :messages="$errors->get('repository_url')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="demo_url" :value="__('Demo URL')" />
        <x-text-input id="demo_url" class="block mt-1 w-full" type="url" name="demo_url" :value="old('demo_url', $project?->demo_url)" />
        <x-input-error :messages="$errors->get('demo_url')" class="mt-2" />
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <x-input-label for="started_at" :value="__('Started at')" />
            <x-text-input id="started_at" class="block mt-1 w-full" type="date" name="started_at"
                :value="old('started_at', optional($project?->started_at)?->format('Y-m-d'))" />
            <x-input-error :messages="$errors->get('started_at')" class="mt-2" />
        </div>
        <div>
            <x-input-label for="finished_at" :value="__('Finished at')" />
            <x-text-input id="finished_at" class="block mt-1 w-full" type="date" name="finished_at"
                :value="old('finished_at', optional($project?->finished_at)?->format('Y-m-d'))" />
            <x-input-error :messages="$errors->get('finished_at')" class="mt-2" />
        </div>
    </div>

    <div class="flex items-center">
        <input id="is_featured" type="checkbox" name="is_featured" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm"
            {{ old('is_featured', $project?->is_featured) ? 'checked' : '' }}>
        <x-input-label for="is_featured" class="ms-2" :value="__('Featured on site')" />
    </div>

    <div>
        <div class="flex items-center justify-between">
            <x-input-label for="seo_title" :value="__('SEO title')" />
            <button type="button" @click="generate('seo_title', 'seo_title')" class="text-xs text-purple-600 hover:text-purple-900 font-medium flex items-center transition-colors">
                <span x-show="!loading['seo_title']">🪄 Otimizar Título</span>
                <span x-show="loading['seo_title']" x-cloak>⏳ Pensando...</span>
            </button>
        </div>
        <x-text-input id="seo_title" class="block mt-1 w-full" type="text" name="seo_title" :value="old('seo_title', $project?->seo_title)" />
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
        <textarea id="seo_description" name="seo_description" rows="3" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">{{ old('seo_description', $project?->seo_description) }}</textarea>
        <x-input-error :messages="$errors->get('seo_description')" class="mt-2" />
    </div>
</div>

