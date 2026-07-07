@extends('layouts.publico')

@section('titulo', 'Assine para continuar')

@section('conteudo')
    <section class="site-assinatura">
        <h1>Seu período de teste terminou</h1>
        <p class="site-assinatura-sub">
            Para continuar usando o Economiza Aí e manter o controle das suas finanças,
            escolha um plano e reative seu acesso.
        </p>

        <div class="site-assinatura-status">
            Status atual da sua conta: <strong>{{ ucfirst($status) }}</strong>
        </div>

        <div class="site-hero-acoes">
            <a href="{{ route('planos') }}" class="site-btn site-btn-grande">Ver planos</a>
        </div>

        <p class="site-assinatura-nota">
            Assim que a cobrança estiver disponível, você poderá assinar por aqui.
            Seus dados continuam salvos e voltam a ficar acessíveis após a assinatura.
        </p>
    </section>
@endsection
