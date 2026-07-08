<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aí Economiza</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="icon" href="{{ asset('images/Logo 2.png') }}" type="image/png" size="16x16">
</head>
<body>
    <div class="cabecalho">
        
        <header>
            <h1>Aí Economiza</h1>  
        </header>
        <main>
            <nav>
                <ul>
                    <li><a href="/dashboard">Inicio</a></li>
                </ul>
            </nav>
            <div class="principal">
                @yield('principal')
            </div>
        </main>
        <footer>
            <p>Feito por:</p>
            <p>Lucas Peira Rocha</p>
            <p>Instagram</p>
            Contatos
        <footer>

    </div>
</body>
</html>