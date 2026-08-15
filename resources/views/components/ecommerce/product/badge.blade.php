@props(['product'])

@if($product->stock > 0)

    <span class="procafe-product-badge procafe-badge-success">
        Disponible
    </span>

@else

    <span class="procafe-product-badge procafe-badge-secondary">
        Sin stock
    </span>

@endif