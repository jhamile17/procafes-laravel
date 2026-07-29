@props([
    'title',
    'subtitle'
])

<div class="customer-header">

    <h1 class="customer-title">
        {{ $title }}
    </h1>

    <p class="customer-subtitle">
        {{ $subtitle }}
    </p>

</div>