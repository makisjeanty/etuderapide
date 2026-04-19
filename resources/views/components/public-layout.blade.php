@props([
    'title' => null,
    'metaDescription' => null,
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title ? $title.' — '.config('app.name') : config('app.name') }}</title>
        @if ($metaDescription)
            <meta name="description" content="{{ $metaDescription }}">
        @endif
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-50 text-gray-900 min-h-screen flex flex-col">
        <header class="border-b border-gray-200 bg-white">
            <div class="max-w-5xl mx-auto px-4 py-4 flex flex-wrap items-center justify-between gap-4">
                <a href="{{ url('/') }}" class="font-semibold text-lg text-gray-900 hover:text-indigo-700">{{ config('app.name') }}</a>
                <nav class="flex flex-wrap items-center gap-4 text-sm">
                    <a href="{{ route('projects.index') }}" class="text-gray-600 hover:text-gray-900">{{ __('Projects') }}</a>
                    <a href="{{ route('services.index') }}" class="text-gray-600 hover:text-gray-900">{{ __('Services') }}</a>
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-indigo-600 hover:text-indigo-800">{{ __('Dashboard') }}</a>
                    @else
                        <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-800">{{ __('Log in') }}</a>
                    @endauth
                </nav>
            </div>
        </header>
        <main class="flex-1 w-full max-w-5xl mx-auto px-4 py-10">
            {{ $slot }}
        </main>
        <footer class="border-t border-gray-200 bg-white py-6 mt-auto text-center text-sm text-gray-500">
            © {{ date('Y') }} {{ config('app.name') }}
        </footer>
    </body>
</html>
