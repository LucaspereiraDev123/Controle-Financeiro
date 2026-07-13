<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('titulo', 'Economiza Certo') — Economiza Certo</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Sora:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/site.css') }}">
    <link rel="icon" href="{{ asset('images/simbolo.png') }}" type="image/png" sizes="64x64">
</head>
<body>
    <div class="site">
        <header class="site-topo">
            <a href="{{ route('home') }}" class="site-logo">
                <img src="{{ asset('images/logo-horizontal.png') }}" alt="Economiza Certo">
            </a>
            <nav class="site-nav">
                <a href="{{ route('home') }}">Início</a>
                <a href="{{ route('planos') }}">Planos</a>
                @auth
                    <a href="{{ route('dashboard') }}" class="site-btn">Meu painel</a>
                @else
                    <a href="{{ route('login') }}">Entrar</a>
                    <a href="{{ route('register') }}" class="site-btn">Criar conta</a>
                @endauth
            </nav>
        </header>

        <main class="site-conteudo">
            @yield('conteudo')
        </main>

        <footer class="site-rodape">
            <p class="site-rodape-slogan">Seu dinheiro, na conta certa.</p>
            <div class="site-rodape-links">
                <a href="{{ route('planos') }}">Planos</a>
                <a href="{{ route('termos') }}">Termos de Uso</a>
                <a href="{{ route('privacidade') }}">Política de Privacidade</a>
            </div>
            <p>© {{ date('Y') }} Economiza Certo — Controle financeiro pessoal. Feito por Lucas Pereira Rocha.</p>
        </footer>
    </div>
</body>
</html>
