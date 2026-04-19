<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Diagnóstico de Negócio - Makis Digital</title>
    <style>
        @page { margin: 0cm; }
        body { font-family: 'Helvetica', sans-serif; margin: 0; padding: 0; color: #334155; }
        .header { background-color: #0f172a; color: white; padding: 40px; text-align: center; }
        .header h1 { margin: 0; font-size: 28px; letter-spacing: 2px; }
        .header p { margin: 10px 0 0; color: #94a3b8; font-size: 14px; }
        .content { padding: 40px; }
        .section { margin-bottom: 30px; }
        .section-title { color: #4f46e5; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px; margin-bottom: 15px; font-size: 18px; text-transform: uppercase; }
        .card { background-color: #f8fafc; border-radius: 8px; padding: 20px; border-left: 5px solid #4f46e5; }
        .footer { position: fixed; bottom: 0; width: 100%; padding: 20px; text-align: center; font-size: 10px; color: #64748b; border-top: 1px solid #e2e8f0; }
        .highlight { color: #4f46e5; font-weight: bold; }
        .summary { font-size: 16px; line-height: 1.6; }
        .score-container { text-align: center; margin: 40px 0; }
        .score-box { display: inline-block; padding: 30px; background-color: #4f46e5; color: white; border-radius: 50%; width: 100px; height: 100px; line-height: 100px; font-size: 32px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1>MAKIS DIGITAL</h1>
        <p>Diagnóstico de Maturidade Digital e IA</p>
    </div>

    <div class="content">
        <div class="section">
            <p>Olá, <span class="highlight">{{ $lead_name }}</span>,</p>
            <p class="summary">Recebemos sua solicitação de análise. Nossa Inteligência Artificial processou as informações do seu negócio e gerou este diagnóstico estratégico exclusivo.</p>
        </div>

        <div class="score-container">
            <p>Sua Pontuação de Maturidade</p>
            <div class="score-box">{{ $score ?? '85' }}</div>
        </div>

        <div class="section">
            <div class="section-title">Análise Estratégica</div>
            <div class="card">
                {!! nl2br(e($analysis_text)) !!}
            </div>
        </div>

        <div class="section">
            <div class="section-title">Próximos Passos Recomendados</div>
            <ul>
                @foreach($recommendations as $item)
                    <li style="margin-bottom: 10px;">{{ $item }}</li>
                @endforeach
            </ul>
        </div>

        <div class="section" style="background-color: #eff6ff; padding: 20px; border-radius: 8px;">
            <p style="margin: 0; font-size: 14px; color: #1e40af;"><strong>Nota da Equipe:</strong> Este diagnóstico é uma análise preliminar baseada em dados. Para um plano de execução detalhado e implementação dessas soluções, agende uma reunião estratégica conosco.</p>
        </div>
    </div>

    <div class="footer">
        &copy; {{ date('Y') }} Makis Digital | contato@makisdigital.com.br | www.makisdigital.com.br
    </div>
</body>
</html>
