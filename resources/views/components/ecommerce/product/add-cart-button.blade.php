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
        'btn',
        'btn-cart',
        'btn-add-to-cart',
        'w-100',
    ]) }}
    data-product-id="{{ $product->id }}"
    data-quantity="{{ $quantity }}"
    @disabled(! $available)
    aria-label="{{ $available ? 'Agregar al carrito' : 'Producto sin stock' }}">

    <i
        class="bi {{ $available ? 'bi-cart-plus' : 'bi-x-circle' }}"
        aria-hidden="true">
    </i>

    <span class="btn-cart-text">
        {{ $available ? 'Agregar al carrito' : 'Sin stock' }}
    </span>

</button>