<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Senha</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="icon" href="{{ asset('images/Logo 2.png') }}" type="image/png" sizes="64x64">
</head>
<body>
    <div class="fundo">
        <div class="fundo-caixa">
            <form class='fundo-caixa-formulario' method="POST" action="{{ route('password.email') }}">
                @csrf
                    <h1>Recuperar Senha</h1>

                    <p>Informe seu e-mail e enviaremos um link para redefinir a senha.</p>

                    @if (session('status'))
                        <div class="fundo-mensagem">{{ session('status') }}</div>
                    @endif

                    @if ($errors->any())
                        <ul class="erros">
                            @foreach ($errors->all() as $erro)
                                <li>{{ $erro }}</li>
                            @endforeach
                        </ul>
                    @endif

                    <label for="email">E-mail</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}">

                    <button>Enviar link</button>
                    <a href="{{ route('login') }}">Voltar ao login</a>
            </form>
        </div>
    </div>
</body>
</html>
