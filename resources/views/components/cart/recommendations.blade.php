@if($products->isNotEmpty())
<section class="customer-card mt-1">
    <div class="customer-card-header">
        <div>
            <span class="customer-card-badge">
                <i class="bi bi-stars me-1"></i>
                Recomendaciones
            </span>
            <h2 class="customer-card-title">
                También podría gustarte
            </h2>
            <p class="customer-card-subtitle">
                Completa tu pedido con productos seleccionados especialmente para ti.
            </p>
        </div>
    </div>
    <div class="customer-card-divider"></div>

    <div class="customer-card-body">

        <div class="row g-4">

            @foreach($products as $product)

                <div class="col-xl-3 col-lg-4 col-md-6">

                    <x-ecommerce.product-card
                        :product="$product" />

                </div>

            @endforeach

        </div>

    </div>

</section>

@endif