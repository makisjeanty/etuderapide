@extends('errors.layout')

@section('title', 'Em Manutenção')
@section('code', '503')
@section('message', 'Voltamos em breve.')

@section('icon')
    <svg class="w-12 h-12 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1h-3a1 1 0 01-1-1v-3a1 1 0 011-1h1a2 2 0 100-4h-1a1 1 0 01-1-1V7a1 1 0 011-1h3a1 1 0 001-1V4z" />
    </svg>
@endsection

@section('description')
    Estamos realizando algumas melhorias na plataforma para te entregar uma experiência ainda melhor. A Makis Digital estará de volta em instantes. Obrigado pela paciência!
@endsection
