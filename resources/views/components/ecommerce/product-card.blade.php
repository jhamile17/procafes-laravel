@props(['product'])

@php
    use Illuminate\Support\Facades\Storage;

    if (!empty($product->image_url)) {
        $image = $product->image_url;
    } elseif (!empty($product->image)) {
        $image = Storage::url($product->image);
    } else {
        $image = asset('images/no-image.png');
    }
@endphp
<div class="product-card h-100">

    <div class="product-image">

        <img
            src="{{ $image }}"
            alt="{{ $product->name }}">

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
                :image="$image"/>

        </div>

    </div>

</div>