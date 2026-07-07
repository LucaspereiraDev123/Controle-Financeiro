@props([
    'titulo' => null,
    'estreito' => false,
])
<section {{ $attributes->class(['painel-bloco', 'painel-form-wrap' => $estreito]) }}>
    @if($titulo || isset($acoes))
        <div class="painel-bloco-cabecalho">
            @if($titulo)
                <h2>{{ $titulo }}</h2>
            @endif
            {{ $acoes ?? '' }}
        </div>
    @endif
    {{ $slot }}
</section>
