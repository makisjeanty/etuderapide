<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AI Pipeline Dashboard</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|outfit:500,700" rel="stylesheet" />

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3 { font-family: 'Outfit', sans-serif; }
        .glass-panel {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
<body class="antialiased min-h-screen bg-gradient-to-br from-slate-950 via-indigo-950 to-slate-900 text-slate-100 selection:bg-indigo-500 selection:text-white flex items-center justify-center p-6">

    <div class="w-full max-w-4xl mx-auto" x-data="aiDashboard()">
        
        <!-- Header -->
        <div class="text-center mb-10 space-y-4">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-tr from-indigo-500 to-purple-500 shadow-lg shadow-indigo-500/30 mb-2">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
            </div>
            <h1 class="text-4xl md:text-5xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-indigo-200 via-white to-purple-200">
                AI Pipeline Architecture
            </h1>
            <p class="text-indigo-200/70 text-lg max-w-2xl mx-auto">
                Laravel <span class="mx-2 text-slate-500">&rarr;</span> Bun (Elysia) <span class="mx-2 text-slate-500">&rarr;</span> Python ML
            </p>
        </div>

        <!-- Main Card -->
        <div class="glass-panel rounded-3xl p-6 md:p-10 shadow-2xl relative overflow-hidden">
            <!-- Decorative blur -->
            <div class="absolute -top-24 -right-24 w-48 h-48 bg-purple-500/20 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-24 -left-24 w-48 h-48 bg-indigo-500/20 rounded-full blur-3xl"></div>

            <div class="relative z-10 grid grid-cols-1 md:grid-cols-2 gap-8">
                
                <!-- Input Section -->
                <div class="flex flex-col space-y-4">
                    <h2 class="text-xl font-semibold flex items-center text-white/90">
                        <svg class="w-5 h-5 mr-2 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        Input Data
                    </h2>
                    
                    <textarea 
                        x-model="inputText" 
                        rows="8" 
                        class="w-full bg-slate-900/50 border border-slate-700/50 rounded-xl p-4 text-slate-200 placeholder-slate-500 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all resize-none shadow-inner"
                        placeholder="Escreva algo para a IA analisar... Ex: 'Preciso de um resumo executivo deste projeto.'"
                    ></textarea>

                    <button 
                        @click="analyzeText" 
                        :disabled="loading || inputText.trim() === ''"
                        class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-semibold rounded-xl text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed transition-all shadow-lg shadow-indigo-600/20 overflow-hidden"
                    >
                        <span class="absolute inset-0 w-full h-full bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-x-full group-hover:animate-[shimmer_1.5s_infinite]"></span>
                        
                        <span x-show="!loading" class="flex items-center">
                            Processar no Python
                            <svg class="ml-2 w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </span>
                        
                        <span x-show="loading" class="flex items-center" style="display: none;">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Analisando (Bun + Python)...
                        </span>
                    </button>
                    
                    <!-- Error Message -->
                    <div x-show="error" x-transition class="mt-2 p-3 bg-red-500/10 border border-red-500/20 rounded-lg text-red-400 text-sm" style="display: none;" x-text="error"></div>
                </div>

                <!-- Output Section -->
                <div class="flex flex-col space-y-4">
                    <h2 class="text-xl font-semibold flex items-center text-white/90">
                        <svg class="w-5 h-5 mr-2 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
                        Result (JSON)
                    </h2>
                    
                    <div class="relative flex-grow">
                        <div class="absolute inset-0 bg-slate-900/80 border border-slate-700/50 rounded-xl overflow-hidden shadow-inner flex flex-col">
                            <!-- Terminal Header -->
                            <div class="bg-slate-950/50 px-4 py-2 border-b border-slate-800 flex items-center space-x-2">
                                <div class="w-3 h-3 rounded-full bg-red-500/80"></div>
                                <div class="w-3 h-3 rounded-full bg-yellow-500/80"></div>
                                <div class="w-3 h-3 rounded-full bg-green-500/80"></div>
                                <span class="ml-2 text-xs font-mono text-slate-500">stdout</span>
                            </div>
                            <!-- Terminal Body -->
                            <div class="p-4 overflow-auto flex-grow font-mono text-sm">
                                <template x-if="!result && !loading">
                                    <div class="text-slate-600 italic flex items-center justify-center h-full">
                                        Aguardando entrada de dados...
                                    </div>
                                </template>
                                
                                <template x-if="loading">
                                    <div class="text-indigo-400 animate-pulse">
                                        [System] -> Sending HTTP POST to Bun (Port 3001)<br>
                                        [Bun] -> Spawning python/analyzer.py<br>
                                        [Python] -> Processing stdin...
                                    </div>
                                </template>
                                
                                <template x-if="result && !loading">
                                    <pre class="text-emerald-400" x-text="JSON.stringify(result, null, 2)"></pre>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Alpine Component Logic -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('aiDashboard', () => ({
                inputText: 'A arquitetura TALL com Bun e Python é incrivelmente performática.',
                result: null,
                loading: false,
                error: null,

                async analyzeText() {
                    this.loading = true;
                    this.error = null;
                    this.result = null;

                    try {
                        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                        
                        const response = await fetch('/analyze', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ text: this.inputText })
                        });

                        const data = await response.json();

                        if (!response.ok) {
                            throw new Error(data.error || 'Erro desconhecido na API');
                        }

                        this.result = data;
                    } catch (err) {
                        this.error = err.message;
                    } finally {
                        this.loading = false;
                    }
                }
            }))
        })
    </script>
</body>
</html>
