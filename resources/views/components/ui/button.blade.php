@props([
    'type' => 'button',
    'variant' => 'primary'
])

@php
    $base = "btn rounded-pill px-3 py-2 fw-medium";

    $variants = [
        'primary' => 'btn-primary',
        'dark' => 'btn-dark',
        'outline' => 'btn-outline-dark',
        'danger' => 'btn-danger',
        'success' => 'btn-success',
    ];
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => $base.' '.$variants[$variant]]) }}>
    {{ $slot }}
</button>