<x-admin.layout titulo="Visão geral" cabecalho="Visão geral" subcabecalho="Métricas do Economiza Certo" nav="dashboard">
    <div class="painel-cards">
        <x-painel.card rotulo="Receita mensal estimada (MRR)"
            :valor="'R$ ' . number_format($mrr, 2, ',', '.')" tom="positivo" destaque />
        <x-painel.card rotulo="Assinantes ativos" :valor="$ativos" />
        <x-painel.card rotulo="Em período de teste" :valor="$emTeste" />
    </div>

    <div class="painel-cards">
        <x-painel.card rotulo="Total de usuários" :valor="$total" />
        <x-painel.card rotulo="Expirados / sem acesso" :valor="$expirados" />
        <x-painel.card rotulo="Novos (30 dias)" :valor="$novos30dias" />
    </div>

    <x-painel.bloco titulo="Cadastros recentes">
        <x-slot:acoes>
            <x-painel.botao :href="route('admin.usuarios')" variante="sec">Ver todos</x-painel.botao>
        </x-slot:acoes>

        <x-painel.tabela :colunas="['Nome', 'E-mail', 'Situação', 'Cadastro']">
            @forelse ($recentes as $u)
                <tr>
                    <td>{{ $u->nome }}</td>
                    <td>{{ $u->email }}</td>
                    <td>
                        <span class="conta-status conta-status--{{ $u->statusAssinatura() }}">
                            @switch($u->statusAssinatura())
                                @case('ativa') Ativa @break
                                @case('trial') Teste @break
                                @default Expirada
                            @endswitch
                        </span>
                    </td>
                    <td>{{ $u->created_at?->format('d/m/Y') ?? '—' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="painel-tabela-vazia">Nenhum usuário cadastrado.</td>
                </tr>
            @endforelse
        </x-painel.tabela>
    </x-painel.bloco>
</x-admin.layout>
