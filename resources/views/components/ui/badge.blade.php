@props([
    'color' => 'secondary',   // primary | success | danger | warning | info | secondary
    'rounded' => true,
])

@php
    $base = 'd-inline-flex align-items-center fw-medium small px-2 py-1';

    $colors = [
        'primary'   => 'bg-primary text-white',
        'success'   => 'bg-success text-white',
        'danger'    => 'bg-danger text-white',
        'warning'   => 'bg-warning text-dark',
        'info'      => 'bg-info text-dark',
        'secondary' => 'bg-secondary text-white',
        'light'     => 'bg-light text-dark border',
        'dark'      => 'bg-dark text-white',
    ];

    $roundedClass = $rounded ? 'rounded-pill' : 'rounded';
@endphp

<span {{ $attributes->merge([
        'class' => $base . ' ' . ($colors[$color] ?? $colors['secondary']) . ' ' . $roundedClass
    ]) }}>
    {{ $slot }}
</span>