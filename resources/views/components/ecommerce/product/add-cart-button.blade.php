@props([
    'product',
    'quantity' => 1,
])

@php
    $available = $product->isAvailable();
@endphp

<button
    type="button"
    {{ $attributes->class([
        'btn-add-to-cart',
        'product-add-btn',
        'w-100',
        'is-disabled' => ! $available,
    ]) }}
    data-product-id="{{ $product->id }}"
    data-quantity="{{ $quantity }}"
    @disabled(! $available)
    aria-label="{{ $available ? 'Agregar al carrito' : 'Producto sin stock' }}">

    <i
        class="bi {{ $available ? 'bi-bag-plus-fill' : 'bi-slash-circle-fill' }}"
        aria-hidden="true">
    </i>

    <span>
        {{ $available ? 'Agregar al carrito' : 'Sin stock' }}
    </span>

</button>