<x-painel.layout titulo="Editar categoria" cabecalho="Editar categoria" subcabecalho="Atualize ou exclua a categoria" nav="categorias">
    <x-painel.bloco estreito>
        <form class="painel-form" id="formularioId" method="POST" action="{{ route('categorias.update', $categoria->id) }}">
            @csrf
            @method('PUT')

            <x-painel.erros />

            <x-form.campo name="nome" label="Nome" :value="$categoria->nome" />

            <x-form.select name="tipo" label="Tipo">
                <option value="Receitas" @selected($categoria->tipo === 'Receitas')>Receitas</option>
                <option value="Despesas" @selected($categoria->tipo === 'Despesas')>Despesas</option>
            </x-form.select>

            <div class="painel-form-acoes">
                <x-painel.botao type="submit" form="formularioId">Salvar</x-painel.botao>
                <x-painel.botao type="submit" variante="perigo" form="deletarFormulario">Excluir</x-painel.botao>
            </div>

            <a href="{{ route('categorias.index') }}" class="painel-voltar">← Voltar às categorias</a>
        </form>

        <form id="deletarFormulario" method="POST" action="{{ route('categorias.destroy', $categoria->id) }}">
            @csrf
            @method('DELETE')
        </form>
    </x-painel.bloco>
</x-painel.layout>
