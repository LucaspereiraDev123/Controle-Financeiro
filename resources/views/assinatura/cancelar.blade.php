<x-painel.layout titulo="Cancelar assinatura" cabecalho="Cancelar assinatura" subcabecalho="Confira o que acontece antes de confirmar" nav="conta">
    <x-painel.bloco titulo="O que acontece ao cancelar">
        <div class="painel-detalhe">
            <div class="painel-detalhe-linha">
                <span>Novas cobranças</span>
                <strong>Encerradas — nada mais será cobrado</strong>
            </div>
            <div class="painel-detalhe-linha">
                <span>Seu acesso continua até</span>
                <strong>{{ $usuario->assinatura_ativa_ate->format('d/m/Y') }}</strong>
            </div>
            <div class="painel-detalhe-linha">
                <span>Depois dessa data</span>
                <strong>Conta em modo leitura</strong>
            </div>
            <div class="painel-detalhe-linha">
                <span>Seus dados</span>
                <strong>Continuam salvos</strong>
            </div>
        </div>

        <p class="painel-planos-nota">
            Você não perde nada agora: usa normalmente até o fim do período que já pagou. Depois disso,
            a conta fica em modo leitura — seus lançamentos continuam aqui para consulta, e você pode
            assinar de novo quando quiser.
        </p>

        <p class="painel-planos-nota">
            <strong>Assinou há menos de 7 dias?</strong> Pelo art. 49 do Código de Defesa do Consumidor
            você pode desistir da contratação e receber de volta o valor cobrado. Para isso, escreva para
            <a href="mailto:contato@economizacerto.com.br">contato@economizacerto.com.br</a> — a devolução
            não é automática por aqui.
        </p>

        <div class="painel-form-acoes">
            <form method="POST" action="{{ route('assinatura.cancelar.confirmar') }}">
                @csrf
                @method('DELETE')
                <x-painel.botao type="submit" variante="perigo">Confirmar cancelamento</x-painel.botao>
            </form>
            <x-painel.botao :href="route('conta')" variante="sec">Voltar sem cancelar</x-painel.botao>
        </div>
    </x-painel.bloco>
</x-painel.layout>
