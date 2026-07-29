@props([
    'type' => 'info',
    'message' => null,
])

@php

$classes = match ($type) {

    'success' => 'auth-status auth-status-success',

    'error' => 'auth-status auth-status-error',

    'warning' => 'auth-status auth-status-warning',

    default => 'auth-status auth-status-info',

};

$icons = match ($type) {

    'success' => 'bi-check-circle-fill',

    'error' => 'bi-x-circle-fill',

    'warning' => 'bi-exclamation-triangle-fill',

    default => 'bi-info-circle-fill',

};

@endphp

@if(filled($message))

<div {{ $attributes->merge(['class' => $classes]) }}>

    <i class="bi {{ $icons }}"></i>

    <span>{{ $message }}</span>

</div>

@endif