<x-painel.layout titulo="Minha conta" cabecalho="Minha conta" subcabecalho="Seus dados e a situação do seu plano" nav="conta">
    @if (session('msg'))
        <div class="painel-alerta">{{ session('msg') }}</div>
    @endif

    {{-- Resumo do plano --}}
    <div class="painel-cards">
        <x-painel.card rotulo="Plano atual" :valor="$planoNome" />
        <x-painel.card rotulo="Valor" :valor="'R$ ' . number_format($planoValor, 2, ',', '.') . '/mês'" />
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

    {{-- Detalhe da assinatura --}}
    <x-painel.bloco titulo="Assinatura">
        @if ($status !== 'ativa')
            <x-slot:acoes>
                <x-painel.botao :href="route('assinatura.expirada')">Assinar agora</x-painel.botao>
            </x-slot:acoes>
        @endif

        <div class="painel-detalhe">
            @if ($status === 'ativa')
                <div class="painel-detalhe-linha">
                    <span>Válida até</span>
                    <strong>{{ $usuario->assinatura_ativa_ate->format('d/m/Y') }}</strong>
                </div>
                <div class="painel-detalhe-linha">
                    <span>Renovação</span>
                    <strong>Automática (mensal)</strong>
                </div>
            @elseif ($status === 'trial')
                <div class="painel-detalhe-linha">
                    <span>Período de teste termina em</span>
                    <strong>{{ $usuario->trial_ends_at->format('d/m/Y') }}</strong>
                </div>
                <div class="painel-detalhe-linha">
                    <span>Dias restantes</span>
                    <strong>{{ $usuario->diasRestantesTrial() }} dia(s)</strong>
                </div>
            @else
                <div class="painel-detalhe-linha">
                    <span>Acesso</span>
                    <strong>Bloqueado — assine para continuar usando</strong>
                </div>
            @endif
        </div>
    </x-painel.bloco>

    {{-- Dados do cliente --}}
    <x-painel.bloco titulo="Dados da conta">
        <div class="painel-detalhe">
            <div class="painel-detalhe-linha">
                <span>Nome</span>
                <strong>{{ $usuario->nome }}</strong>
            </div>
            <div class="painel-detalhe-linha">
                <span>E-mail</span>
                <strong>{{ $usuario->email }}</strong>
            </div>
            <div class="painel-detalhe-linha">
                <span>E-mail verificado</span>
                <strong>{{ $usuario->email_verified_at ? 'Sim' : 'Não' }}</strong>
            </div>
            <div class="painel-detalhe-linha">
                <span>Cliente desde</span>
                <strong>{{ $usuario->created_at?->format('d/m/Y') ?? '—' }}</strong>
            </div>
            @if ($usuario->termos_aceitos_em)
                <div class="painel-detalhe-linha">
                    <span>Termos aceitos em</span>
                    <strong>{{ $usuario->termos_aceitos_em->format('d/m/Y') }}</strong>
                </div>
            @endif
        </div>
    </x-painel.bloco>
</x-painel.layout>
