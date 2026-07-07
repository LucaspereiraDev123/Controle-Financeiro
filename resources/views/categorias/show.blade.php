<x-painel.layout titulo="Categoria" cabecalho="Detalhes da categoria" nav="categorias">
    <x-painel.bloco estreito>
        @if (isset($msg))
            <p>Categoria não encontrada.</p>
        @else
            <div class="painel-detalhe">
                <div class="painel-detalhe-linha">
                    <span>Nome</span>
                    <strong>{{ $categoria->nome }}</strong>
                </div>
                <div class="painel-detalhe-linha">
                    <span>Tipo</span>
                    <strong><x-painel.tag :tipo="$categoria->tipo" /></strong>
                </div>
            </div>

            <div class="painel-form-acoes">
                <x-painel.botao :href="route('categorias.edit', $categoria->id)">Editar</x-painel.botao>
                <x-painel.botao :href="route('categorias.index')" variante="sec">Voltar</x-painel.botao>
            </div>
        @endif
    </x-painel.bloco>
</x-painel.layout>
