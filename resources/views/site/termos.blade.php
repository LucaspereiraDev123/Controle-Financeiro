@extends('layouts.publico')

@section('titulo', 'Termos de Uso')

@section('conteudo')
    <article class="site-legal">
        <h1>Termos de Uso</h1>
        <p class="site-legal-data">Última atualização: {{ date('d/m/Y') }}</p>

        <p class="site-legal-aviso">
            ⚠️ Documento em rascunho. Este texto é um ponto de partida e deve ser revisado
            por um profissional jurídico antes do uso em produção.
        </p>

        <h2>1. Aceitação</h2>
        <p>
            Ao criar uma conta e utilizar o Economiza Aí, você declara ter lido e concordado com estes
            Termos de Uso e com a <a href="{{ route('privacidade') }}">Política de Privacidade</a>.
        </p>

        <h2>2. O serviço</h2>
        <p>
            O Economiza Aí é uma ferramenta de controle financeiro pessoal que permite registrar receitas,
            despesas e categorias, e visualizar saldos. O serviço é fornecido "como está", podendo evoluir
            com o tempo.
        </p>

        <h2>3. Cadastro e conta</h2>
        <ul>
            <li>Você deve fornecer informações verdadeiras e manter seu e-mail atualizado.</li>
            <li>É necessário confirmar o e-mail para acessar a aplicação.</li>
            <li>Você é responsável por manter a confidencialidade da sua senha e pelas ações realizadas na sua conta.</li>
        </ul>

        <h2>4. Período de teste e planos</h2>
        <p>
            Novos usuários têm um período de teste gratuito de 14 dias. Após esse período, o uso contínuo
            poderá exigir a contratação de um plano pago, conforme divulgado na página de
            <a href="{{ route('planos') }}">Planos</a>. Detalhes de cobrança serão apresentados no momento da contratação.
        </p>

        <h2>5. Uso aceitável</h2>
        <p>
            Você concorda em não utilizar a plataforma para fins ilícitos, em não tentar acessar dados de
            outros usuários e em não comprometer a segurança ou o funcionamento do serviço.
        </p>

        <h2>6. Cancelamento</h2>
        <p>
            Você pode deixar de usar o serviço a qualquer momento e solicitar a exclusão da sua conta e
            dos seus dados, conforme a Política de Privacidade.
        </p>

        <h2>7. Propriedade intelectual</h2>
        <p>
            A marca, o layout e o código da plataforma pertencem ao Economiza Aí. Os dados financeiros que
            você cadastra são seus.
        </p>

        <h2>8. Limitação de responsabilidade</h2>
        <p>
            O Economiza Aí é uma ferramenta de apoio e não constitui aconselhamento financeiro. Não nos
            responsabilizamos por decisões tomadas com base nas informações registradas por você.
        </p>

        <h2>9. Lei aplicável</h2>
        <p>
            Estes Termos são regidos pelas leis da República Federativa do Brasil.
        </p>

        <h2>10. Contato</h2>
        <p>
            Dúvidas sobre estes Termos? Escreva para
            <a href="mailto:contato@economizaai.com.br">contato@economizaai.com.br</a>.
        </p>
    </article>
@endsection
