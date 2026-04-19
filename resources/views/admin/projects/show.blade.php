<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $project->title }}</h2>
            <a href="{{ route('admin.projects.edit', $project) }}" class="text-sm text-indigo-600 hover:text-indigo-900">{{ __('Edit') }}</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 space-y-4 text-gray-900">
                <p class="text-sm text-gray-500 font-mono">{{ $project->slug }} · {{ $project->status->value }}</p>
                @if ($project->summary)
                    <p class="font-medium">{{ $project->summary }}</p>
                @endif
                @if ($project->description)
                    <div class="whitespace-pre-wrap">{{ $project->description }}</div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
