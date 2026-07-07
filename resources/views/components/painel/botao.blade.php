@props([
    'href' => null,
    'variante' => 'primary',
    'type' => 'submit',
    'form' => null,
])
@php
    $classe = match ($variante) {
        'sec' => 'painel-btn-sec',
        'perigo' => 'painel-btn-perigo',
        default => 'painel-btn',
    };
@endphp
@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classe]) }}>{{ $slot }}</a>
@else
    <button type="{{ $type }}" @if($form) form="{{ $form }}" @endif {{ $attributes->merge(['class' => $classe]) }}>{{ $slot }}</button>
@endif
