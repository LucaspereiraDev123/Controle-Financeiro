<x-auth.layout titulo="Criar conta" subtitulo="Comece a organizar suas finanças">
    <form class="painel-form auth-form" method="POST" action="{{ route('register') }}">
        @csrf

        <x-painel.erros />

        <x-form.campo name="nome" label="Nome" />

        <x-form.campo name="email" label="E-mail" type="email" />

        <x-form.campo name="password" label="Senha" type="password" />

        <x-form.campo name="password_confirmation" label="Confirmar senha" type="password" />

        <label class="auth-aceite">
            <input type="checkbox" name="aceite_termos" value="1" {{ old('aceite_termos') ? 'checked' : '' }}>
            <span>Li e aceito os
                <a href="{{ route('termos') }}" target="_blank">Termos de Uso</a> e a
                <a href="{{ route('privacidade') }}" target="_blank">Política de Privacidade</a>.
            </span>
        </label>

        <x-painel.botao type="submit">Registrar</x-painel.botao>

        <div class="auth-links">
            <a href="{{ route('login') }}">Já possui conta? Entrar</a>
        </div>
    </form>
</x-auth.layout>
