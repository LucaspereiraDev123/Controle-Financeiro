<x-auth.layout titulo="Confirme seu e-mail">
    <p class="auth-texto">
        Enviamos um link de verificação para o seu e-mail. Clique nele para ativar sua conta.
    </p>

    @if (session('status') == 'verification-link-sent')
        <div class="auth-status">Um novo link de verificação foi enviado para o seu e-mail.</div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}" class="auth-form">
        @csrf
        <x-painel.botao type="submit">Reenviar e-mail de verificação</x-painel.botao>
    </form>

    <div class="auth-links">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="painel-btn-sec">Sair</button>
        </form>
    </div>
</x-auth.layout>
