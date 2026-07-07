<x-painel.layout titulo="Transação" cabecalho="Detalhes da transação">
    <x-painel.bloco estreito>
        @if (isset($msg))
            <p>Transação não encontrada.</p>
        @else
            <div class="painel-detalhe">
                <div class="painel-detalhe-linha">
                    <span>Tipo</span>
                    <strong><x-painel.tag :tipo="$transacao->tipo" /></strong>
                </div>
                <div class="painel-detalhe-linha">
                    <span>Descrição</span>
                    <strong>{{ $transacao->descricao }}</strong>
                </div>
                <div class="painel-detalhe-linha">
                    <span>Valor</span>
                    <strong class="{{ $transacao->tipo === 'Receitas' ? 'positivo' : 'negativo' }}">R$ {{ number_format($transacao->valor, 2, ',', '.') }}</strong>
                </div>
                <div class="painel-detalhe-linha">
                    <span>Categoria</span>
                    <strong>{{ $transacao->categoria->nome }}</strong>
                </div>
                <div class="painel-detalhe-linha">
                    <span>Criado em</span>
                    <strong>{{ $transacao->created_at->format('d/m/Y H:i') }}</strong>
                </div>
                <div class="painel-detalhe-linha">
                    <span>Atualizado em</span>
                    <strong>{{ $transacao->updated_at->format('d/m/Y H:i') }}</strong>
                </div>
            </div>

            <div class="painel-form-acoes">
                <x-painel.botao :href="route('transacoes.edit', $transacao->id)">Editar</x-painel.botao>
                <x-painel.botao :href="route('dashboard')" variante="sec">Voltar</x-painel.botao>
            </div>
        @endif
    </x-painel.bloco>
</x-painel.layout>
