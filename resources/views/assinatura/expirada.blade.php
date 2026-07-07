<x-auth.layout titulo="Seu período de teste terminou" subtitulo="Para continuar usando o Economiza Aí e manter o controle das suas finanças, assine o plano e reative seu acesso.">
    @if (session('msg'))
        <div class="auth-status">{{ session('msg') }}</div>
    @endif

    <div class="auth-status" style="background: rgba(240,198,116,0.12); border-color: rgba(240,198,116,0.4); color: #f0c674;">
        Status atual da sua conta: <strong>{{ ucfirst($status) }}</strong>
    </div>

    @if ($mpConfigurado)
        <form method="POST" action="{{ route('assinatura.checkout') }}" class="auth-form">
            @csrf
            <x-painel.botao type="submit">
                Assinar agora — R$ {{ number_format(config('services.mercadopago.plano_valor'), 2, ',', '.') }}/mês
            </x-painel.botao>
        </form>
        <p class="auth-texto" style="margin-top: 1rem; margin-bottom: 0;">
            Você será direcionado ao ambiente seguro do Mercado Pago para concluir o pagamento.
        </p>
    @else
        <div class="auth-form">
            <x-painel.botao :href="route('planos')">Ver planos</x-painel.botao>
        </div>
        <p class="auth-texto" style="margin-top: 1rem; margin-bottom: 0;">
            A assinatura online estará disponível em breve. Seus dados continuam salvos.
        </p>
    @endif

    <div class="auth-links">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="painel-btn-sec">Sair</button>
        </form>
    </div>
</x-auth.layout>
