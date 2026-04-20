<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Acesso - Makis Digital</title>
    
    <!-- Fonts & Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        :root {
            --bg-main: #020617;
            --primary: #f59e0b;
            --primary-glow: rgba(245, 158, 11, 0.3);
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg-main);
            background-image: 
                radial-gradient(circle at 20% 20%, rgba(245, 158, 11, 0.05) 0%, transparent 40%),
                radial-gradient(circle at 80% 80%, rgba(59, 130, 246, 0.05) 0%, transparent 40%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #f8fafc;
            margin: 0;
            overflow: hidden;
        }

        .login-card {
            background: rgba(30, 41, 59, 0.5);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 2rem;
            padding: 2.5rem 1.5rem;
            width: 90%;
            max-width: 450px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        .premium-input {
            width: 100%;
            background: rgba(15, 23, 42, 0.6) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            border-radius: 1rem !important;
            padding: 0.875rem 1rem 0.875rem 3rem !important;
            color: white !important;
            outline: none !important;
        }

        .premium-input:focus {
            border-color: var(--primary) !important;
            box-shadow: 0 0 15px var(--primary-glow) !important;
        }

        .login-btn {
            width: 100%;
            background: var(--primary);
            color: #020617;
            padding: 1rem;
            border-radius: 1rem;
            font-weight: 700;
            margin-top: 1rem;
            transition: all 0.3s ease;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px var(--primary-glow);
        }

        .input-group { position: relative; }
        .input-group i {
            position: absolute;
            left: 1.25rem;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="text-center mb-10">
            <div class="w-16 h-16 bg-amber-500 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg shadow-amber-500/20 rotate-[-10deg]">
                <i class="fas fa-bolt text-slate-900 text-3xl"></i>
            </div>
            <h1 class="text-2xl font-bold tracking-tight">Makis <span class="text-amber-500">Digital</span></h1>
            <p class="text-slate-400 text-sm mt-1">Acesso ao Centro de Comando</p>
        </div>

        {{ $slot }}
    </div>
</body>
</html>
