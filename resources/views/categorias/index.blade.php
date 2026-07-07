<x-painel.layout titulo="Categorias" cabecalho="Categorias" subcabecalho="Organize seus lançamentos por categoria" nav="categorias">
    <x-slot:acoes>
        <x-painel.botao :href="route('categorias.create')">+ Nova categoria</x-painel.botao>
    </x-slot:acoes>

    <x-painel.bloco>
        <x-painel.tabela :colunas="['Tipo', 'Nome', 'Opções']">
            @forelse ($categorias as $c)
                <tr>
                    <td><x-painel.tag :tipo="$c->tipo" /></td>
                    <td>{{ $c->nome }}</td>
                    <td class="painel-tabela-acoes">
                        <a href="{{ route('categorias.show', $c->id) }}">Mostrar</a>
                        <a href="{{ route('categorias.edit', $c->id) }}">Editar</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="painel-tabela-vazia">Nenhuma categoria encontrada.</td>
                </tr>
            @endforelse
        </x-painel.tabela>
    </x-painel.bloco>
</x-painel.layout>
