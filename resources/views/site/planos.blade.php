@extends('layouts.publico')

@section('titulo', 'Planos')

@section('conteudo')
    <section class="site-planos">
        <h1>Um plano simples, sem pegadinha</h1>
        <p class="site-planos-sub">Comece com 14 dias grátis. Cancele quando quiser.</p>

        <div class="site-plano">
            <h2>Plano Economiza Certo</h2>
            <p class="site-plano-preco">
                <span class="site-plano-valor">R$ 19,90</span>
                <span class="site-plano-periodo">/mês</span>
            </p>
            <ul class="site-plano-itens">
                <li>Transações ilimitadas</li>
                <li>Categorias personalizadas</li>
                <li>Dashboard com filtros e saldos</li>
                <li>Seus dados isolados e seguros</li>
                <li>Suporte por e-mail</li>
            </ul>
            <a href="{{ route('register') }}" class="site-btn site-btn-grande">Começar teste grátis</a>
            <p class="site-plano-nota">Sem cartão de crédito para começar.</p>
        </div>
    </section>
@endsection
