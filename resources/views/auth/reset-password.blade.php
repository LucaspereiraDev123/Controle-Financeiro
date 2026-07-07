<x-auth.layout titulo="Redefinir senha" subtitulo="Defina uma nova senha para sua conta">
    <form class="painel-form auth-form" method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <x-painel.erros />

        <x-form.campo name="email" label="E-mail" type="email" :value="$request->email" />

        <x-form.campo name="password" label="Nova senha" type="password" />

        <x-form.campo name="password_confirmation" label="Confirmar nova senha" type="password" />

        <x-painel.botao type="submit">Redefinir senha</x-painel.botao>

        <div class="auth-links">
            <a href="{{ route('login') }}">Voltar ao login</a>
        </div>
    </form>
</x-auth.layout>
