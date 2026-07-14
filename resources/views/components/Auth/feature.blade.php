@props([
    'icon' => 'bi-check-circle-fill'
])

<div class="auth-item">

    <i class="bi {{ $icon }}"></i>

    <span>

        {{ $slot }}

    </span>

</div>