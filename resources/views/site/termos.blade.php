@extends('layouts.publico')

@section('titulo', 'Termos de Uso')

@section('conteudo')
    <article class="site-legal">
        <h1>Termos de Uso</h1>
        <p class="site-legal-data">Última atualização: 08/07/2026</p>

        <p>
            Estes Termos de Uso ("Termos") regem o acesso e a utilização do <strong>Economiza Certo</strong>
            (o "Serviço"). Ao criar uma conta ou utilizar o Serviço, você concorda integralmente com estes
            Termos e com a <a href="{{ route('privacidade') }}">Política de Privacidade</a>. Se você não
            concordar, não utilize a plataforma.
        </p>

        <h2>1. Identificação</h2>
        <p>
            O Serviço é operado pelo responsável pelo Economiza Certo — <strong>56.048.183 LUCAS PEREIRA ROCHA 56.048.183/0001-70</strong>, doravante denominado "nós". Contato:
            <a href="mailto:contato@economizacerto.com.br">contato@economizacerto.com.br</a>.
        </p>

        <h2>2. O serviço</h2>
        <p>
            O Economiza Certo é uma ferramenta de controle financeiro pessoal que permite registrar receitas,
            despesas e categorias e visualizar saldos e relatórios. O Serviço é uma ferramenta de apoio à
            organização financeira e <strong>não constitui aconselhamento financeiro, contábil ou de
            investimentos</strong>.
        </p>

        <h2>3. Cadastro e conta</h2>
        <ul>
            <li>Você deve fornecer informações verdadeiras, completas e atualizadas;</li>
            <li>É necessário confirmar o endereço de e-mail para acessar a aplicação;</li>
            <li>Você é responsável por manter a confidencialidade da sua senha e por todas as ações
                realizadas na sua conta;</li>
            <li>A conta é individual e intransferível. Comunique-nos imediatamente qualquer uso não
                autorizado.</li>
        </ul>

        <h2>4. Período de teste gratuito</h2>
        <p>
            Novos usuários têm um período de teste gratuito de <strong>14 dias</strong>, sem necessidade
            de cartão de crédito para começar. Ao fim do período, o uso contínuo das funcionalidades
            depende da contratação de um plano pago.
        </p>

        <h2>5. Planos, pagamento e renovação</h2>
        <ul>
            <li>O plano do Economiza Certo custa <strong>R$ 19,90 por mês</strong>, conforme divulgado na
                página de <a href="{{ route('planos') }}">Planos</a>;</li>
            <li>Os pagamentos são processados por meio do <strong>Mercado Pago</strong>. Os dados do cartão
                são fornecidos e tratados diretamente no ambiente do Mercado Pago; não temos acesso ao
                número do seu cartão;</li>
            <li>A assinatura é <strong>recorrente</strong>: salvo cancelamento, é renovada automaticamente
                a cada período, com nova cobrança no mesmo valor então vigente;</li>
            <li>Eventuais reajustes de preço serão comunicados previamente e passarão a valer na renovação
                seguinte.</li>
        </ul>

        <h2>6. Cancelamento e direito de arrependimento</h2>
        <p>
            Você pode cancelar a assinatura a qualquer momento; o cancelamento encerra as renovações
            futuras, e o acesso permanece disponível até o fim do período já pago. Nos termos do art. 49 do
            Código de Defesa do Consumidor, você pode <strong>desistir da contratação em até 7 (sete) dias</strong>
            a contar da assinatura paga, com devolução dos valores eventualmente cobrados nesse prazo.
            Solicitações devem ser enviadas para
            <a href="mailto:contato@economizacerto.com.br">contato@economizacerto.com.br</a>.
        </p>

        <h2>7. Uso aceitável</h2>
        <p>Ao usar o Serviço, você concorda em não:</p>
        <ul>
            <li>Utilizá-lo para fins ilícitos ou que violem direitos de terceiros;</li>
            <li>Tentar acessar dados de outros usuários ou áreas restritas da plataforma;</li>
            <li>Comprometer a segurança, a estabilidade ou o funcionamento do Serviço;</li>
            <li>Reproduzir, copiar ou explorar comercialmente o Serviço sem autorização.</li>
        </ul>

        <h2>8. Disponibilidade e alterações do serviço</h2>
        <p>
            O Serviço é fornecido "como está" e "conforme disponível". Podemos alterar, suspender ou
            descontinuar funcionalidades, bem como realizar manutenções, buscando minimizar impactos.
            Não garantimos disponibilidade ininterrupta e livre de falhas.
        </p>

        <h2>9. Propriedade intelectual</h2>
        <p>
            A marca, o layout, o código e os demais elementos da plataforma pertencem ao Economiza Certo.
            <strong>Os dados financeiros que você cadastra são seus</strong> — apenas os tratamos para
            prestar o Serviço, conforme a Política de Privacidade.
        </p>

        <h2>10. Limitação de responsabilidade</h2>
        <p>
            O Economiza Certo é uma ferramenta de apoio e não se responsabiliza por decisões financeiras
            tomadas com base nas informações registradas por você, nem por perdas decorrentes de uso
            indevido, indisponibilidade temporária ou de fatores fora do nosso controle razoável.
        </p>

        <h2>11. Suspensão e encerramento</h2>
        <p>
            Podemos suspender ou encerrar contas que violem estes Termos ou a legislação aplicável.
            Você pode encerrar sua conta a qualquer momento e solicitar a exclusão dos seus dados,
            conforme a <a href="{{ route('privacidade') }}">Política de Privacidade</a>.
        </p>

        <h2>12. Alterações destes Termos</h2>
        <p>
            Estes Termos podem ser atualizados. A data da última atualização é indicada no topo desta
            página, e mudanças relevantes serão comunicadas pelos canais da plataforma. O uso continuado
            após as alterações representa concordância com a versão vigente.
        </p>

        <h2>13. Lei aplicável e foro</h2>
        <p>
            Estes Termos são regidos pelas leis da República Federativa do Brasil. Fica eleito o foro da
            comarca de <strong>Aracruz - ES</strong> para dirimir eventuais controvérsias, sem
            prejuízo do foro de domicílio do consumidor, quando aplicável.
        </p>

        <h2>14. Contato</h2>
        <p>
            Dúvidas sobre estes Termos? Escreva para
            <a href="mailto:contato@economizacerto.com.br">contato@economizacerto.com.br</a>.
        </p>
    </article>
@endsection
