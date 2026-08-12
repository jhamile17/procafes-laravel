<div class="card shadow-sm border-0 whislist-container">

    {{-- Header --}}
    <div class="card-header bg-white border-bottom-0 py-4">

        <div class="d-flex justify-content-between align-items-center">

            <div>

                <h3 class="fw-bold mb-1">

                    <i class="bi bi-heart-fill text-danger me-2"></i>

                    Mis Favoritos

                </h3>

                <p class="text-muted mb-0">

                    Guarda tus productos favoritos para comprarlos más adelante.

                </p>

            </div>

            <span class="badge bg-danger rounded-pill px-3 py-2">

                {{ $count }} {{ Str::plural('producto', $count) }}

            </span>

        </div>

    </div>

    {{-- Body --}}
    <div class="card-body p-0">

        @forelse($products as $product)
                <div class="wishlist-card">

                    <div class="wishlist-image-wrapper">
                        <img
                            src="{{ $product->image }}"
                            alt="{{ $product->name }}"
                            class="wishlist-image">
                    </div>

                    <div class="wishlist-content">

                        <h5 class="wishlist-name">
                            {{ $product->name }}
                        </h5>

                        <div class="wishlist-category">
                            {{ $product->category }}
                        </div>

                        <div class="wishlist-price">
                            {{ $product->formatted_price }}
                        </div>

                        <span class="badge bg-{{ $product->stock_badge }}">
                            {{ $product->stock_status }}
                        </span>

                    </div>

                    <div class="wishlist-actions">

                        <button
                            type="button"
                            class="btn wishlist-cart"
                            data-product="{{ $product->product_id }}"
                            data-quantity="1"
                            title="Agregar al carrito">

                            <i class="bi bi-cart-plus"></i>

                        </button>

                        <button
                            type="button"
                            class="btn wishlist-remove"
                            data-product="{{ $product->product_id }}"
                            title="Eliminar de favoritos">

                            <i class="bi bi-trash"></i>

                        </button>

                    </div>

                </div>


        @empty

            <div class="text-center py-5">

                <i class="bi bi-heart display-1 text-secondary"></i>

                <h4 class="mt-3">

                    Aún no tienes favoritos

                </h4>

                <p class="text-muted mb-4">

                    Explora nuestro catálogo y guarda los productos que más te gusten.

                </p>

                <a
                    href="{{ route('products') }}"
                    class="customer-btn">

                    <i class="bi bi-shop me-2"></i>

                    Ir al catálogo

                </a>

            </div>

        @endforelse

    </div>

</div>