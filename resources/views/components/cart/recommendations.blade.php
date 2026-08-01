@if($products->isNotEmpty())

<div class="cart-recommendations mt-5">

    <div class="mb-4">

        <span class="section-badge">
            Recomendaciones
        </span>

        <h3 class="mt-2 mb-2">
            También podría gustarte
        </h3>

        <p class="text-muted mb-0">
            Descubre otros productos para complementar tu compra.
        </p>

    </div>

    <div class="row g-4">

        @foreach($products as $product)

            <div class="col-xl-3 col-lg-4 col-md-6">

                <x-ecommerce.product-card
                    :product="$product" />

            </div>

        @endforeach

    </div>

</div>

@endif