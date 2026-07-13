@props([
    'titulo' => 'Admin',
    'cabecalho' => 'Administração',
    'subcabecalho' => null,
    'nav' => null,
])
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $titulo }} — Admin · Economiza Certo</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Sora:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/painel.css') }}">
    <link rel="icon" href="{{ asset('images/simbolo.png') }}" type="image/png" sizes="64x64">
    {{ $head ?? '' }}
</head>
<body class="painel">
    <aside class="painel-sidebar">
        <a href="{{ route('admin.dashboard') }}" class="painel-logo">
            <img class="painel-logo-mark" src="{{ asset('images/simbolo.png') }}" alt="Economiza Certo">
            <span>Economiza Certo</span>
        </a>

        <p class="painel-menu-titulo">Administração</p>
        <nav class="painel-nav">
            <a href="{{ route('admin.dashboard') }}" class="painel-nav-item {{ $nav === 'dashboard' ? 'ativo' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12l9-9 9 9"/><path d="M5 10v10h14V10"/></svg>
                <span>Visão geral</span>
            </a>
            <a href="{{ route('admin.usuarios') }}" class="painel-nav-item {{ $nav === 'usuarios' ? 'ativo' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="8" r="3.5"/><path d="M2 20c0-3.3 3.1-5 7-5s7 1.7 7 5"/><path d="M17 8h5M19.5 5.5v5"/></svg>
                <span>Usuários</span>
            </a>
        </nav>

        <p class="painel-menu-titulo">Atalhos</p>
        <nav class="painel-nav">
            <a href="{{ route('dashboard') }}" class="painel-nav-item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 6l-6 6 6 6"/><path d="M3 12h13a5 5 0 0 1 5 5v1"/></svg>
                <span>Voltar ao app</span>
            </a>
        </nav>

        <div class="painel-sidebar-rodape">
            <div class="painel-usuario">
                <span class="painel-avatar">{{ strtoupper(substr(auth()->user()->nome ?? 'A', 0, 1)) }}</span>
                <div class="painel-usuario-info">
                    <strong>{{ auth()->user()->nome }}</strong>
                    <small>Administrador</small>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="painel-sair">Sair</button>
            </form>
        </div>
    </aside>

    <main class="painel-main">
        <header class="painel-topo">
            <div>
                <h1>{{ $cabecalho }}</h1>
                @if($subcabecalho)
                    <p class="painel-topo-sub">{{ $subcabecalho }}</p>
                @endif
            </div>
            <div class="painel-topo-acoes">{{ $acoes ?? '' }}</div>
        </header>

        <div class="painel-conteudo">
            {{ $slot }}
        </div>
    </main>

    {{ $scripts ?? '' }}
</body>
</html>
