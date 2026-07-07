@props([
    'name',
    'label',
    'type' => 'text',
    'value' => null,
    'placeholder' => null,
])
<label for="{{ $name }}">{{ $label }}</label>
<input
    type="{{ $type }}"
    name="{{ $name }}"
    id="{{ $name }}"
    value="{{ old($name, $value) }}"
    placeholder="{{ $placeholder }}"
    {{ $attributes }}
>
