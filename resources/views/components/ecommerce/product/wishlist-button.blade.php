@props(['product'])

@auth

<button
    type="button"
    class="product-wishlist btn-wishlist"
    data-id="{{ $product->id }}">

    <i class="bi bi-heart"></i>

</button>

@else

<a
    href="{{ route('login') }}"
    class="product-wishlist">

    <i class="bi bi-heart"></i>

</a>

@endauth