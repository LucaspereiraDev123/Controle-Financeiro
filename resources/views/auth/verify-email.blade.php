<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificar E-mail</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="icon" href="{{ asset('images/Logo 2.png') }}" type="image/png" sizes="64x64">
</head>
<body>
    <div class="fundo">
        <div class="fundo-caixa">
            <div class="fundo-caixa-formulario">
                <h1>Confirme seu E-mail</h1>

                <p>Enviamos um link de verificação para o seu e-mail. Clique nele para ativar sua conta.</p>

                @if (session('status') == 'verification-link-sent')
                    <div class="fundo-mensagem">
                        Um novo link de verificação foi enviado para o seu e-mail.
                    </div>
                @endif

                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button>Reenviar e-mail de verificação</button>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit">Sair</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
