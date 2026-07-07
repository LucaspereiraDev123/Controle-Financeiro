<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir Senha</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="icon" href="{{ asset('images/Logo 2.png') }}" type="image/png" sizes="64x64">
</head>
<body>
    <div class="fundo">
        <div class="fundo-caixa">
            <form class='fundo-caixa-formulario' method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <h1>Redefinir Senha</h1>

                    @if ($errors->any())
                        <ul class="erros">
                            @foreach ($errors->all() as $erro)
                                <li>{{ $erro }}</li>
                            @endforeach
                        </ul>
                    @endif

                    <label for="email">E-mail</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $request->email) }}">

                    <label for="password">Nova senha</label>
                    <input type="password" name="password" id="password">

                    <label for="password_confirmation">Confirmar nova senha</label>
                    <input type="password" name="password_confirmation" id="password_confirmation">

                    <button>Redefinir senha</button>
                    <a href="{{ route('login') }}">Voltar ao login</a>
            </form>
        </div>
    </div>
</body>
</html>
