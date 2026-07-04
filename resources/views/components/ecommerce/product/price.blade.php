@props(['product'])

<div class="product-price">

    S/ {{ number_format($product->price,2) }}

</div>