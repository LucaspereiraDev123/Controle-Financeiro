<x-painel.layout titulo="Transações" cabecalho="Transações cadastradas" subcabecalho="Todos os seus lançamentos">
    <x-slot:acoes>
        <x-painel.botao :href="route('transacoes.create')">+ Nova transação</x-painel.botao>
    </x-slot:acoes>

    <x-painel.bloco>
        <x-painel.tabela :colunas="['Tipo', 'Descrição', 'Valor', 'Categoria', 'Opções']">
            @forelse ($transacoes as $t)
                <tr>
                    <td><x-painel.tag :tipo="$t->tipo" /></td>
                    <td>{{ $t->descricao }}</td>
                    <td class="{{ $t->tipo === 'Receitas' ? 'positivo' : 'negativo' }}">
                        {{ $t->tipo === 'Receitas' ? '+' : '−' }} R$ {{ number_format($t->valor, 2, ',', '.') }}
                    </td>
                    <td>{{ $t->categoria->nome }}</td>
                    <td class="painel-tabela-acoes">
                        <a href="{{ route('transacoes.show', $t->id) }}">Mostrar</a>
                        <a href="{{ route('transacoes.edit', $t->id) }}">Editar</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="painel-tabela-vazia">Nenhuma transação encontrada.</td>
                </tr>
            @endforelse
        </x-painel.tabela>

        <x-painel.paginacao :paginator="$transacoes" />
    </x-painel.bloco>
</x-painel.layout>
