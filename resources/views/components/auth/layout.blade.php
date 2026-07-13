@props([
    'titulo' => 'Economiza Certo',
    'subtitulo' => null,
])
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $titulo }} — Economiza Certo</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Sora:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/painel.css') }}">
    <link rel="icon" href="{{ asset('images/simbolo.png') }}" type="image/png" sizes="64x64">
</head>
<body class="auth">
    <div class="auth-caixa">
        <a href="{{ url('/') }}" class="auth-logo">
            <img class="painel-logo-mark" src="{{ asset('images/simbolo.png') }}" alt="Economiza Certo">
            <span>Economiza Certo</span>
        </a>

        <h1 class="auth-titulo">{{ $titulo }}</h1>
        @if($subtitulo)
            <p class="auth-sub">{{ $subtitulo }}</p>
        @endif

        {{ $slot }}
    </div>
</body>
</html>
