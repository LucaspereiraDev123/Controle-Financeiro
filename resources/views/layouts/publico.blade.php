<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('titulo', 'Economiza Aí') — Economiza Aí</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="icon" href="{{ asset('images/Logo 2.png') }}" type="image/png" sizes="64x64">
</head>
<body>
    <div class="site">
        <header class="site-topo">
            <a href="{{ route('home') }}" class="site-logo">Economiza Aí</a>
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
            <div class="site-rodape-links">
                <a href="{{ route('planos') }}">Planos</a>
                <a href="{{ route('termos') }}">Termos de Uso</a>
                <a href="{{ route('privacidade') }}">Política de Privacidade</a>
            </div>
            <p>© {{ date('Y') }} Economiza Aí — Controle financeiro pessoal. Feito por Lucas Pereira Rocha.</p>
        </footer>
    </div>
</body>
</html>
