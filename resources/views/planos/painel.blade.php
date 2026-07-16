@php
    $valor = config('services.mercadopago.plano_valor');
    $valorFormatado = 'R$ ' . number_format($valor, 2, ',', '.');
@endphp

<x-painel.layout titulo="Planos" cabecalho="Planos" subcabecalho="Um plano simples, sem pegadinha" nav="planos">
    @if (session('msg'))
        <div class="painel-alerta">{{ session('msg') }}</div>
    @endif

    <div class="painel-cards">
        <x-painel.card rotulo="Plano" :valor="config('services.mercadopago.plano_nome')" />
        <x-painel.card rotulo="Valor" :valor="$valorFormatado . '/mês'" />
        <div class="painel-card">
            <span class="painel-card-rotulo">Situação</span>
            <span class="painel-card-valor">
                <span class="conta-status conta-status--{{ $status }}">
                    @switch($status)
                        @case('ativa') Assinatura ativa @break
                        @case('trial') Período de teste @break
                        @default Expirada
                    @endswitch
                </span>
            </span>
        </div>
    </div>

    <x-painel.bloco :titulo="config('services.mercadopago.plano_nome')">
        <div class="painel-detalhe">
            <div class="painel-detalhe-linha">
                <span>Transações ilimitadas</span><strong>Incluído</strong>
            </div>
            <div class="painel-detalhe-linha">
                <span>Categorias personalizadas</span><strong>Incluído</strong>
            </div>
            <div class="painel-detalhe-linha">
                <span>Dashboard com filtros e saldos</span><strong>Incluído</strong>
            </div>
            <div class="painel-detalhe-linha">
                <span>Seus dados isolados e seguros</span><strong>Incluído</strong>
            </div>
            <div class="painel-detalhe-linha">
                <span>Suporte por e-mail</span><strong>Incluído</strong>
            </div>
        </div>

        @if ($status === 'ativa')
            <p class="painel-planos-nota">
                Sua assinatura está ativa até
                <strong>{{ $usuario->assinatura_ativa_ate->format('d/m/Y') }}</strong>
                e renova automaticamente. Para ver os detalhes, acesse
                <a href="{{ route('conta') }}">Minha conta</a>.
            </p>
        @elseif ($mpConfigurado)
            @if ($status === 'trial')
                <p class="painel-planos-nota">
                    Você ainda tem <strong>{{ $usuario->diasRestantesTrial() }} dia(s)</strong> de teste grátis.
                    Ao assinar agora, a cobrança é feita hoje e os dias restantes são perdidos.
                </p>
            @else
                <p class="painel-planos-nota">
                    Seu período de teste terminou e sua conta está em <strong>modo leitura</strong>:
                    seus dados continuam aqui, mas você não consegue lançar nem editar.
                    Assine para voltar a usar.
                </p>
            @endif

            <form method="POST" action="{{ route('assinatura.checkout') }}" class="painel-planos-acao">
                @csrf
                <x-painel.botao type="submit">Assinar agora — {{ $valorFormatado }}/mês</x-painel.botao>
            </form>

            <p class="painel-planos-nota">
                Você será direcionado ao ambiente seguro do Mercado Pago para concluir o pagamento.
                O cartão é digitado lá — nosso servidor não vê os dados dele.
            </p>
        @else
            <p class="painel-planos-nota">
                A assinatura online estará disponível em breve. Seus dados continuam salvos.
            </p>
        @endif
    </x-painel.bloco>
</x-painel.layout>
