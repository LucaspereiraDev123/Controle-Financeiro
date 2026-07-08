@extends('layouts.publico')

@section('titulo', 'Organize suas finanças')

@section('conteudo')
    <section class="site-hero">
        <h1>Controle o seu dinheiro sem complicação</h1>
        <p class="site-hero-sub">
            O <strong>Aí Economiza</strong> ajuda você a registrar receitas e despesas,
            organizar por categorias e enxergar seu saldo em tempo real — tudo num só lugar.
        </p>
        <div class="site-hero-acoes">
            <a href="{{ route('register') }}" class="site-btn site-btn-grande">Começar grátis</a>
            <a href="{{ route('planos') }}" class="site-link">Ver planos</a>
        </div>
        <p class="site-hero-nota">Teste grátis por 14 dias. Sem cartão de crédito.</p>
    </section>

    <section class="site-recursos">
        <div class="site-card">
            <h3>Receitas e despesas</h3>
            <p>Cadastre suas transações e mantenha o histórico sempre organizado.</p>
        </div>
        <div class="site-card">
            <h3>Categorias</h3>
            <p>Agrupe seus lançamentos por categoria e entenda para onde vai seu dinheiro.</p>
        </div>
        <div class="site-card">
            <h3>Saldo em tempo real</h3>
            <p>Veja total, receitas, despesas e saldo calculados automaticamente.</p>
        </div>
        <div class="site-card">
            <h3>Seus dados protegidos</h3>
            <p>Cada conta enxerga apenas os próprios dados, com login seguro e verificação de e-mail.</p>
        </div>
    </section>
@endsection
