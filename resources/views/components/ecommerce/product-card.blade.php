@props(['product'])

<div class="product-card">

    <div class="product-image">

        <img
            src="{{ $product->image_url }}"
            alt="Producto {{ $product->name }}">

        <x-ecommerce.product.badge
            :product="$product"/>

        <x-ecommerce.product.wishlist-button
            :product="$product"/>

    </div>

    <div class="card-body d-flex flex-column">

        @if($product->category)
            <small class="product-category">
                {{ $product->category->name }}
            </small>
        @endif

        <h5 class="product-title">
            {{ $product->name }}
        </h5>

        @if($product->brand)
            <small class="product-brand">
                {{ $product->brand->name }}
            </small>
        @endif

        <x-ecommerce.product.rating/>

        <div class="mt-auto">

            <x-ecommerce.product.price
                :product="$product"/>

            <x-ecommerce.product.add-cart-button
                :product="$product"
                :image="$product->image_url"/>

        </div>

    </div>

</div>