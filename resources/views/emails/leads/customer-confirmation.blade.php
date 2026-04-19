@extends('emails.layout')

@section('content')
    <h2 style="color: #111827;">Olá, {{ $lead->name }}!</h2>
    <p>Recebemos sua mensagem com sucesso. É um prazer ter você em contato com a <strong>Makis Digital</strong>.</p>
    
    <p>Nosso time de especialistas já foi notificado e estamos analisando sua solicitação para te dar a melhor resposta técnica possível.</p>

    <div style="border-left: 4px solid #4f46e5; padding-left: 20px; margin: 20px 0; color: #4b5563;">
        <p><strong>O que acontece agora?</strong></p>
        <p>1. Analisamos seu interesse em <em>{{ $lead->service_interest ?? 'nossas soluções' }}</em>.</p>
        <p>2. Um consultor entrará em contato via e-mail ou telefone em até 24 horas úteis.</p>
        <p>3. Vamos agendar uma conversa rápida para entender seus objetivos.</p>
    </div>

    <p>Enquanto aguarda, que tal dar uma olhada em nossos cases de sucesso mais recentes?</p>

    <a href="{{ url('/projects') }}" class="button" style="color: white;">Ver Cases de Sucesso</a>

    <p style="margin-top: 30px; font-size: 14px; color: #6b7280;">Atenciosamente,<br>Equipe Makis Digital</p>
@endsection
