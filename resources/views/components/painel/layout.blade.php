@props([
    'titulo' => 'Painel',
    'cabecalho' => 'Painel',
    'subcabecalho' => null,
    'nav' => null,
])
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $titulo }} — Economiza Aí</title>
    <link rel="stylesheet" href="{{ asset('css/painel.css') }}">
    <link rel="icon" href="{{ asset('images/Logo 2.png') }}" type="image/png" sizes="64x64">
    {{ $head ?? '' }}
</head>
<body class="painel">
    <aside class="painel-sidebar">
        <a href="{{ route('dashboard') }}" class="painel-logo">
            <span class="painel-logo-mark">E</span>
            <span>Economiza Aí</span>
        </a>

        <p class="painel-menu-titulo">Menu</p>
        <nav class="painel-nav">
            <a href="{{ route('dashboard') }}" class="painel-nav-item {{ $nav === 'dashboard' ? 'ativo' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="9" rx="1.5"/><rect x="14" y="3" width="7" height="5" rx="1.5"/><rect x="14" y="12" width="7" height="9" rx="1.5"/><rect x="3" y="16" width="7" height="5" rx="1.5"/></svg>
                <span>Início</span>
            </a>
            <a href="{{ route('categorias.index') }}" class="painel-nav-item {{ $nav === 'categorias' ? 'ativo' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 7h16M4 12h16M4 17h10"/></svg>
                <span>Categorias</span>
            </a>
            <a href="{{ route('transacoes.create') }}" class="painel-nav-item {{ $nav === 'nova' ? 'ativo' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M12 8v8M8 12h8"/></svg>
                <span>Nova transação</span>
            </a>
        </nav>

        <p class="painel-menu-titulo">Suporte</p>
        <nav class="painel-nav">
            <a href="{{ route('planos') }}" class="painel-nav-item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2l3 7h7l-5.5 4 2 7L12 17l-6.5 3 2-7L2 9h7z"/></svg>
                <span>Planos</span>
            </a>
        </nav>

        <div class="painel-sidebar-rodape">
            <div class="painel-usuario">
                <span class="painel-avatar">{{ strtoupper(substr(auth()->user()->nome ?? 'U', 0, 1)) }}</span>
                <div class="painel-usuario-info">
                    <strong>{{ auth()->user()->nome }}</strong>
                    <small>{{ auth()->user()->email }}</small>
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
