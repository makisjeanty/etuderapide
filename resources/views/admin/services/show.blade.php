<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $service->name }}</h2>
            <a href="{{ route('admin.services.edit', $service) }}" class="text-sm text-indigo-600 hover:text-indigo-900">{{ __('Edit') }}</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 space-y-4 text-gray-900">
                <p class="text-sm text-gray-500 font-mono">{{ $service->slug }}</p>
                @if ($service->short_description)
                    <p>{{ $service->short_description }}</p>
                @endif
                @if ($service->full_description)
                    <div class="whitespace-pre-wrap">{{ $service->full_description }}</div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
