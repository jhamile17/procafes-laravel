<div class="card wishlist-container">

    {{-- =====================================================
         HEADER
    ====================================================== --}}

    <div class="card-header wishlist-header">

        <div class="wishlist-header-content">

            <div class="wishlist-header-title">

                <i class="bi bi-heart-fill"></i>

                <div>

                    <h3>
                        Mis Favoritos
                    </h3>

                    <p>
                        Guarda tus productos favoritos para comprarlos más adelante.
                    </p>

                </div>

            </div>


            {{-- =================================================
                 CONTADOR
            ================================================== --}}

            <span
                id="wishlistCount"
                class="wishlist-count">

                {{ $count }}

                {{ Str::plural('producto', $count) }}

            </span>

        </div>

    </div>


    {{-- =====================================================
         BODY
    ====================================================== --}}

    <div class="card-body wishlist-body">

        @forelse($products as $product)

            {{-- =================================================
                 PRODUCTO
            ================================================== --}}

            <div class="wishlist-card">

                {{-- =================================================
                     IMAGEN
                ================================================== --}}

                <div class="wishlist-image-wrapper">

                    <img
                        src="{{ $product->image }}"
                        alt="{{ $product->name }}"
                        class="wishlist-image"
                        loading="lazy"
                    >

                </div>


                {{-- =================================================
                     INFORMACIÓN
                ================================================== --}}

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

                    <span class="wishlist-status">
                        {{ $product->stock_status }}
                    </span>

                </div>


                {{-- =================================================
                     ACCIONES
                ================================================== --}}

                <div class="wishlist-actions">

                    {{-- =================================================
                         AGREGAR AL CARRITO
                         UNA SOLA UNIDAD
                    ================================================== --}}

                    <button
                        type="button"
                        class="wishlist-cart"
                        data-product="{{ $product->product_id }}"
                        data-quantity="1"
                        title="Agregar al carrito"
                        aria-label="Agregar {{ $product->name }} al carrito"
                    >

                        <i class="bi bi-cart-plus"></i>

                    </button>


                    {{-- =================================================
                         ELIMINAR DE FAVORITOS
                    ================================================== --}}

                    <button
                        type="button"
                        class="wishlist-remove"
                        data-product="{{ $product->product_id }}"
                        title="Eliminar de favoritos"
                        aria-label="Eliminar {{ $product->name }} de favoritos"
                    >

                        <i class="bi bi-trash"></i>

                    </button>

                </div>

            </div>

        @empty

            {{-- =================================================
                 FAVORITOS VACÍOS
            ================================================== --}}

            <div class="wishlist-empty">

                <i class="bi bi-heart"></i>

                <h3>
                    Aún no tienes favoritos
                </h3>

                <p>
                    Explora nuestro catálogo y guarda los productos
                    que más te gusten.
                </p>

                <a
                    href="{{ route('products') }}"
                    class="wishlist-empty-btn"
                >

                    <i class="bi bi-shop"></i>

                    <span>
                        Ir al catálogo
                    </span>

                </a>

            </div>

        @endforelse

    </div>

</div>