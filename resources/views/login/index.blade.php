<x-auth.layout titulo="Entrar" subtitulo="Acesse sua conta para continuar">
    <form class="painel-form auth-form" method="POST" action="{{ route('login') }}">
        @csrf

        <x-painel.erros />

        <x-form.campo name="email" label="E-mail" type="email" />

        <x-form.campo name="password" label="Senha" type="password" />

        <label class="auth-check">
            <input type="checkbox" name="remember"> Lembrar de mim
        </label>

        <x-painel.botao type="submit">Entrar</x-painel.botao>

        <div class="auth-links">
            <a href="{{ route('password.request') }}">Esqueci minha senha</a>
            <a href="{{ route('register') }}">Criar uma conta</a>
        </div>
    </form>
</x-auth.layout>
