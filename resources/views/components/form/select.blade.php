@props([
    'name',
    'label',
])
<label for="{{ $name }}">{{ $label }}</label>
<select name="{{ $name }}" id="{{ $name }}" {{ $attributes }}>
    {{ $slot }}
</select>
