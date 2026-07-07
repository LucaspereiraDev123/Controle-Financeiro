<x-painel.layout titulo="Nova transação" cabecalho="Nova transação" subcabecalho="Cadastre uma receita ou despesa" nav="nova">
    <x-painel.bloco estreito>
        <form class="painel-form" method="POST" action="{{ route('transacoes.store') }}">
            @csrf

            <x-painel.erros />

            <x-form.select name="tipo" label="Tipo">
                <option value="Receitas" @selected(old('tipo') === 'Receitas')>Receitas</option>
                <option value="Despesas" @selected(old('tipo') === 'Despesas')>Despesas</option>
            </x-form.select>

            <x-form.campo name="descricao" label="Descrição" placeholder="Ex: Netflix" />

            <x-form.campo name="valor" label="Valor (R$)" type="number" step="0.01" min="0" placeholder="0,00" />

            <x-form.select name="categoria_id" label="Categoria">
                @foreach ($categorias as $c)
                    <option value="{{ $c->id }}" @selected((string) old('categoria_id') === (string) $c->id)>{{ $c->nome }}</option>
                @endforeach
            </x-form.select>

            <div class="painel-form-acoes">
                <x-painel.botao type="submit">Salvar</x-painel.botao>
                <x-painel.botao type="reset" variante="sec">Limpar</x-painel.botao>
            </div>

            <a href="{{ route('dashboard') }}" class="painel-voltar">← Voltar ao painel</a>
        </form>
    </x-painel.bloco>
</x-painel.layout>
