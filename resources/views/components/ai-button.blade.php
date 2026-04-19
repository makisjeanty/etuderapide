<button type="button" 
    {{ $attributes->merge(['class' => 'inline-flex items-center px-3 py-1 bg-indigo-50 border border-indigo-300 rounded-md font-semibold text-xs text-indigo-700 uppercase tracking-widest shadow-sm hover:bg-indigo-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150']) }}
    data-ai-action="{{ $action }}"
    data-ai-target="{{ $target }}">
    <svg class="w-4 h-4 mr-1 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
    </svg>
    {{ $slot ?? 'IA' }}
</button>
