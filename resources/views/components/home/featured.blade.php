@props([
    'products' => [],
])

<section class="featured-section">

    <div class="container">

        <div class="featured-header">

           <x-ui.section-title
                tag="PRODUCTOS"
                title="Productos Destacados"
                description="Descubre nuestros cafés, bebidas y productos más populares."
            />

            <a
                href="{{ route('products') }}"
                class="btn-link-arrow">

                Ver más productos

                <i class="bi bi-arrow-right"></i>

            </a>

        </div>

        <div class="products-grid">

            @forelse($products->take(4) as $product)

                <div class="fade-up">

                    <x-ecommerce.product-card
                        :product="$product" />

                </div>

            @empty

                <div class="featured-empty">

                    <i class="bi bi-cup-hot fs-1 mb-3"></i>

                    <h5>

                        No hay productos destacados

                    </h5>

                    <p>

                        Próximamente encontrarás aquí nuestros productos más populares.

                    </p>

                </div>

            @endforelse

        </div>

    </div>

</section>