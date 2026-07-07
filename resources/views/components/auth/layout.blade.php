@props([
    'titulo' => 'Economiza Aí',
    'subtitulo' => null,
])
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $titulo }} — Economiza Aí</title>
    <link rel="stylesheet" href="{{ asset('css/painel.css') }}">
    <link rel="icon" href="{{ asset('images/Logo 2.png') }}" type="image/png" sizes="64x64">
</head>
<body class="auth">
    <div class="auth-caixa">
        <a href="{{ url('/') }}" class="auth-logo">
            <span class="painel-logo-mark">E</span>
            <span>Economiza Aí</span>
        </a>

        <h1 class="auth-titulo">{{ $titulo }}</h1>
        @if($subtitulo)
            <p class="auth-sub">{{ $subtitulo }}</p>
        @endif

        {{ $slot }}
    </div>
</body>
</html>
