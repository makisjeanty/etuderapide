@extends('emails.layout')

@section('content')
    <h2 style="color: #111827;">Olá, Administrador!</h2>
    <p>Você acabou de receber um novo contato pelo site.</p>
    
    <div style="background: #f9fafb; padding: 20px; border-radius: 8px; margin: 20px 0;">
        <p><strong>Nome:</strong> {{ $lead->name }}</p>
        <p><strong>E-mail:</strong> {{ $lead->email }}</p>
        <p><strong>Interesse:</strong> <span class="badge">{{ $lead->service_interest ?? 'Geral' }}</span></p>
        <p><strong>Mensagem:</strong></p>
        <p style="font-style: italic; color: #4b5563;">"{{ $lead->message }}"</p>
    </div>

    <p>Recomendamos responder este cliente em menos de 2 horas para aumentar as chances de conversão.</p>

    <a href="{{ route('admin.leads.show', $lead) }}" class="button" style="color: white;">Ver no Painel Admin</a>
@endsection
