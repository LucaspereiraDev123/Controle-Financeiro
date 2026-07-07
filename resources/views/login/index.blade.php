
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="icon" href="{{ asset('images/Logo 2.png') }}" type="image/png" sizes="64x64">
</head>
<body>
    <div class="fundo">
        <div class="fundo-caixa">
            <form class='fundo-caixa-formulario' method="POST" action="{{ route('signin') }}">
                @csrf
                    <h1>Economiza Aí</h1>

                    @if ($errors->any())
                        <ul class="erros">
                            @foreach ($errors->all() as $erro)
                                <li>{{ $erro }}</li>
                            @endforeach
                        </ul>
                    @endif

                    <label for="email">E-mail</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}">
    
                    <label for="password">Senha</label>
                    <input type="password" name="password" id="password">
    
                    <button>Entrar</button>
                    <a href="{{ route('usuarios.create') }}">Registrar</a>
                        
            </form>
        </div>
        <p>Feito por Lucas Pereira Rocha</p>
    </div>
</body>
</html>
