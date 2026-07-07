<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar</title>
    <link rel="icon" href="{{ asset('images/Logo 2.png') }}" type="image/png" sizes="64x64">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="fundo">
        <div class="fundo-caixa">
            <form class="fundo-caixa-formulario" method="POST" action="{{ route('usuarios.registerStore') }}">
                @csrf
                    <h1>Faça seu Registro</h1>

                    @if ($errors->any())
                        <ul class="erros">
                            @foreach ($errors->all() as $erro)
                                <li>{{ $erro }}</li>
                            @endforeach
                        </ul>
                    @endif

                    <label for="nome">Nome</label>
                    <input type="text" name="nome" id="nome" value="{{ old('nome') }}">

                    <label for="email">E-mail</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}">

                    <label for="password">Senha</label>
                    <input type="password" name="password" id="password">

                    <label for="password_confirmation">Confirmar Senha</label>
                    <input type="password" name="password_confirmation" id="password_confirmation">

                    <button>Registrar</button>
                    <a href="{{ route('login') }}">Ja possui conta ?</a>
            </form>
        </div>
    </div>
</body>
</html>
