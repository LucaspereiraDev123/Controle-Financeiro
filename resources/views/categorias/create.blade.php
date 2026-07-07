<x-painel.layout titulo="Nova categoria" cabecalho="Nova categoria" subcabecalho="Crie uma categoria de receita ou despesa" nav="categorias">
    <x-painel.bloco estreito>
        <form class="painel-form" method="POST" action="{{ route('categorias.store') }}">
            @csrf

            <x-painel.erros />

            <x-form.campo name="nome" label="Nome" placeholder="Ex: Alimentação" />

            <x-form.select name="tipo" label="Tipo">
                <option value="Receitas" @selected(old('tipo') === 'Receitas')>Receitas</option>
                <option value="Despesas" @selected(old('tipo') === 'Despesas')>Despesas</option>
            </x-form.select>

            <div class="painel-form-acoes">
                <x-painel.botao type="submit">Salvar</x-painel.botao>
                <x-painel.botao type="reset" variante="sec">Limpar</x-painel.botao>
            </div>

            <a href="{{ route('categorias.index') }}" class="painel-voltar">← Voltar às categorias</a>
        </form>
    </x-painel.bloco>
</x-painel.layout>
