@props(['product'])

@if($product->stock > 0)

    <span class="product-badge badge-success">
        Disponible
    </span>

@else

    <span class="product-badge badge-secondary">
        Sin stock
    </span>

@endif