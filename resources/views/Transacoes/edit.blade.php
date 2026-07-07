<x-painel.layout titulo="Editar transação" cabecalho="Editar transação" subcabecalho="Atualize ou exclua o lançamento">
    <x-painel.bloco estreito>
        <form class="painel-form" method="POST" action="{{ route('transacoes.update', $transacao->id) }}">
            @csrf
            @method('PUT')

            <x-painel.erros />

            <x-form.select name="tipo" label="Tipo">
                <option value="Receitas" @selected($transacao->tipo === 'Receitas')>Receitas</option>
                <option value="Despesas" @selected($transacao->tipo === 'Despesas')>Despesas</option>
            </x-form.select>

            <x-form.campo name="descricao" label="Descrição" :value="$transacao->descricao" />

            <x-form.campo name="valor" label="Valor (R$)" type="number" step="0.01" min="0" :value="$transacao->valor" />

            <x-form.select name="categoria_id" label="Categoria">
                @foreach ($categorias as $c)
                    <option value="{{ $c->id }}" @selected($c->id === $transacao->categoria_id)>{{ $c->nome }}</option>
                @endforeach
            </x-form.select>

            <div class="painel-form-acoes">
                <x-painel.botao type="submit">Salvar</x-painel.botao>
                <x-painel.botao type="submit" variante="perigo" form="deletar">Excluir</x-painel.botao>
            </div>

            <a href="{{ route('dashboard') }}" class="painel-voltar">← Voltar ao painel</a>
        </form>

        <form method="POST" id="deletar" action="{{ route('transacoes.destroy', $transacao->id) }}">
            @csrf
            @method('DELETE')
        </form>
    </x-painel.bloco>
</x-painel.layout>
