@props([
    'rotulo',
    'valor',
    'tom' => null,
    'destaque' => false,
])
<div {{ $attributes->class(['painel-card', 'painel-card-destaque' => $destaque]) }}>
    <span class="painel-card-rotulo">{{ $rotulo }}</span>
    <strong class="painel-card-valor {{ $tom }}">{{ $valor }}</strong>
</div>
