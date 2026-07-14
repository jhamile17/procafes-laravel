@props(['product'])

<button
    type="button"
    class="product-wishlist btn-wishlist"
    data-product-id="{{ $product->id }}"
    aria-label="Agregar a favoritos">

    <i class="bi bi-heart"></i>

</button>