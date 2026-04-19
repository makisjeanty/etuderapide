@extends('errors.layout')

@section('title', 'Página não encontrada')
@section('code', '404')
@section('message', 'Oops! Caminho não encontrado.')

@section('icon')
    <svg class="w-12 h-12 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 9.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
@endsection

@section('description')
    Parece que a página que você está procurando não existe ou foi movida para um novo endereço. Não se preocupe, você pode voltar para o início ou nos enviar uma mensagem se precisar de ajuda.
@endsection
