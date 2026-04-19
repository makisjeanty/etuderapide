<!DOCTYPE html>
<html lang="pt-BR" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') | Makis Digital</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Outfit:wght@700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        heading: ['Outfit', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <style>
        body { background-color: #0f172a; color: #f8fafc; }
        .glass { background: rgba(30, 41, 59, 0.7); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.1); }
    </style>
</head>
<body class="h-full flex items-center justify-center p-6">
    <div class="max-w-xl w-full text-center">
        <div class="mb-8 inline-flex p-4 rounded-2xl bg-indigo-500/10 border border-indigo-500/20">
            @yield('icon')
        </div>
        
        <h1 class="text-5xl md:text-6xl font-bold font-heading mb-4 text-white">@yield('code')</h1>
        <h2 class="text-2xl font-semibold mb-6 text-slate-200">@yield('message')</h2>
        
        <div class="glass p-8 rounded-3xl mb-10">
            <p class="text-slate-400 mb-8 leading-relaxed">
                @yield('description')
            </p>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ url('/') }}" class="px-8 py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl transition-all shadow-lg shadow-indigo-500/25">
                    Voltar para Home
                </a>
                <a href="{{ url('/contact') }}" class="px-8 py-3 bg-slate-800 hover:bg-slate-700 text-white font-bold rounded-xl transition-all border border-slate-700">
                    Falar com Suporte
                </a>
            </div>
        </div>
        
        <p class="text-slate-500 text-sm">
            &copy; {{ date('Y') }} Makis Digital. Excelência em cada detalhe.
        </p>
    </div>
</body>
</html>
