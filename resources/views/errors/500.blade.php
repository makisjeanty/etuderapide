@extends('errors.layout')

@section('title', 'Erro Interno')
@section('code', '500')
@section('message', 'Instabilidade Detectada.')

@section('icon')
    <svg class="w-12 h-12 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
    </svg>
@endsection

@section('description')
    Identificamos uma instabilidade temporária no nosso sistema. Nossa equipe técnica já foi notificada automaticamente. Por favor, tente atualizar a página em alguns instantes.
@endsection
