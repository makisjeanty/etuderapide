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
        body { background-color: #faf9f5; color: #141413; }
        .ant-card { background: #ffffff; border: 1px solid rgba(20, 20, 19, 0.10); }
    </style>
</head>
<body class="h-full flex items-center justify-center p-6">
    <div class="max-w-xl w-full text-center">
        <div class="mb-8 inline-flex p-4 rounded-2xl bg-[#d97757]/10 border border-[#d97757]/20 text-[#d97757]">
            @yield('icon')
        </div>
        
        <h1 class="text-5xl md:text-6xl font-bold font-heading mb-4 text-[#141413]">@yield('code')</h1>
        <h2 class="text-2xl font-semibold mb-6 text-[#3d3d3a]">@yield('message')</h2>
        
        <div class="ant-card p-8 rounded-3xl mb-10 shadow-xs">
            <p class="text-[#5e5d59] mb-8 leading-relaxed">
                @yield('description')
            </p>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ url('/') }}" class="px-8 py-3 bg-[#d97757] hover:bg-[#c6613f] text-white font-bold rounded-xl transition-all shadow-sm">
                    Voltar para Home
                </a>
                <a href="{{ url('/contato') }}" class="px-8 py-3 bg-[#141413] hover:bg-[#3d3d3a] text-[#faf9f5] font-bold rounded-xl transition-all border border-[#141413]">
                    Falar com Suporte
                </a>
            </div>
        </div>
        
        <p class="text-[#5e5d59] text-sm">
            &copy; {{ date('Y') }} Makis Digital. Excelência em cada detalhe.
        </p>
    </div>
</body>
</html>
