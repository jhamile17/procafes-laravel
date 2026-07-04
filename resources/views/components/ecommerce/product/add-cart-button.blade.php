@props([
    'product',
    'image'
])

<button

    type="button"

    class="btn btn-primary w-100 btn-add-to-cart"

    data-id="{{ $product->id }}"

    data-name="{{ $product->name }}"

    data-price="{{ $product->price }}"

    data-image="{{ $image }}"

    data-url="{{ route('products') }}"

    {{ $product->stock <= 0 ? 'disabled' : '' }}>

    <i class="bi bi-cart-plus me-2"></i>

    Agregar al carrito

</button>