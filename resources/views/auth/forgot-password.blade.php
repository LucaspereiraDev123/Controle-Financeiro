<x-auth.layout titulo="Recuperar senha" subtitulo="Informe seu e-mail e enviaremos um link para redefinir a senha.">
    <form class="painel-form auth-form" method="POST" action="{{ route('password.email') }}">
        @csrf

        @if (session('status'))
            <div class="auth-status">{{ session('status') }}</div>
        @endif

        <x-painel.erros />

        <x-form.campo name="email" label="E-mail" type="email" />

        <x-painel.botao type="submit">Enviar link</x-painel.botao>

        <div class="auth-links">
            <a href="{{ route('login') }}">Voltar ao login</a>
        </div>
    </form>
</x-auth.layout>
