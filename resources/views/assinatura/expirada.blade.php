<x-auth.layout titulo="Seu período de teste terminou" subtitulo="Para continuar usando o Economiza Aí e manter o controle das suas finanças, escolha um plano e reative seu acesso.">
    <div class="auth-status" style="background: rgba(240,198,116,0.12); border-color: rgba(240,198,116,0.4); color: #f0c674;">
        Status atual da sua conta: <strong>{{ ucfirst($status) }}</strong>
    </div>

    <div class="auth-form">
        <x-painel.botao :href="route('planos')">Ver planos</x-painel.botao>
    </div>

    <p class="auth-texto" style="margin-top: 1.25rem; margin-bottom: 0;">
        Assim que a cobrança estiver disponível, você poderá assinar por aqui.
        Seus dados continuam salvos e voltam a ficar acessíveis após a assinatura.
    </p>

    <div class="auth-links">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="painel-btn-sec">Sair</button>
        </form>
    </div>
</x-auth.layout>
