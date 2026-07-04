@props([
    'products' => [],
    'categories' => [],
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
                class="featured-link">

                Ver todos

                <i class="bi bi-arrow-right ms-2"></i>

            </a>

        </div>

        <div class="featured-filters">

            <button
                type="button"
                class="filter-button active">
                Todos
            </button>

            @foreach($categories as $category)

                <button
                    type="button"
                    class="filter-button">

                    {{ $category->name }}

                </button>

            @endforeach

        </div>

        <div class="products-grid">

            @forelse($products as $product)

                <x-ecommerce.product-card
                    :product="$product" />

            @empty

                <div class="featured-empty">

                    <i class="bi bi-cup-hot fs-1 mb-3"></i>

                    <h5>No hay productos destacados</h5>

                    <p>
                        Próximamente encontrarás aquí nuestros productos más populares.
                    </p>

                </div>

            @endforelse

        </div>

    </div>

</section>