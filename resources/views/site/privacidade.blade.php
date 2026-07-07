@extends('layouts.publico')

@section('titulo', 'Política de Privacidade')

@section('conteudo')
    <article class="site-legal">
        <h1>Política de Privacidade</h1>
        <p class="site-legal-data">Última atualização: {{ date('d/m/Y') }}</p>

        <p class="site-legal-aviso">
            ⚠️ Documento em rascunho. Este texto é um ponto de partida orientado à LGPD
            (Lei nº 13.709/2018) e deve ser revisado por um profissional jurídico antes do uso em produção.
        </p>

        <h2>1. Quem somos</h2>
        <p>
            O Economiza Aí é uma aplicação de controle financeiro pessoal. Para fins da LGPD,
            atuamos como <strong>controladores</strong> dos dados pessoais tratados na plataforma.
        </p>

        <h2>2. Dados que coletamos</h2>
        <ul>
            <li><strong>Cadastro:</strong> nome e e-mail.</li>
            <li><strong>Autenticação:</strong> senha (armazenada de forma criptografada) e data de verificação do e-mail.</li>
            <li><strong>Uso do serviço:</strong> transações financeiras e categorias que você cadastra.</li>
            <li><strong>Consentimento:</strong> data e hora do aceite dos Termos e desta Política.</li>
        </ul>

        <h2>3. Para que usamos seus dados</h2>
        <ul>
            <li>Criar e manter sua conta e autenticar seu acesso.</li>
            <li>Fornecer as funcionalidades de controle financeiro.</li>
            <li>Enviar e-mails essenciais (verificação de conta e redefinição de senha).</li>
            <li>Cumprir obrigações legais e garantir a segurança da plataforma.</li>
        </ul>

        <h2>4. Base legal</h2>
        <p>
            O tratamento se dá com base na <strong>execução do contrato</strong> (prestação do serviço),
            no <strong>consentimento</strong> fornecido no cadastro e no <strong>cumprimento de obrigações legais</strong>,
            conforme o art. 7º da LGPD.
        </p>

        <h2>5. Compartilhamento</h2>
        <p>
            Não vendemos seus dados. Podemos utilizar fornecedores de infraestrutura e envio de e-mail
            estritamente para operar o serviço, sempre com salvaguardas adequadas.
        </p>

        <h2>6. Seus direitos como titular</h2>
        <p>Nos termos do art. 18 da LGPD, você pode solicitar:</p>
        <ul>
            <li>Confirmação da existência de tratamento e acesso aos dados;</li>
            <li>Correção de dados incompletos ou desatualizados;</li>
            <li>Anonimização, bloqueio ou eliminação de dados desnecessários;</li>
            <li>Portabilidade e eliminação dos dados tratados com base no consentimento;</li>
            <li>Revogação do consentimento a qualquer momento.</li>
        </ul>

        <h2>7. Retenção e segurança</h2>
        <p>
            Mantemos seus dados enquanto sua conta estiver ativa ou conforme exigido por lei.
            Adotamos medidas técnicas como criptografia de senha, controle de acesso por usuário e
            comunicação protegida.
        </p>

        <h2>8. Contato do Encarregado (DPO)</h2>
        <p>
            Para exercer seus direitos ou tirar dúvidas sobre privacidade, escreva para
            <a href="mailto:privacidade@economizaai.com.br">privacidade@economizaai.com.br</a>.
        </p>

        <h2>9. Alterações</h2>
        <p>
            Esta política pode ser atualizada. Mudanças relevantes serão comunicadas pelos canais da plataforma.
        </p>
    </article>
@endsection
