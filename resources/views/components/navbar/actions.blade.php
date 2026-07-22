{{--==========================================================================
    NAVBAR ACTIONS
    --------------------------------------------------------------------------
    Acciones rápidas disponibles desde la barra de navegación.
==========================================================================--}}

<div class="navbar-actions">

    {{-- Favoritos --}}
    <a
        href="{{ route('wishlist.index') }}"
        class="navbar-action"
        aria-label="Lista de favoritos">

        <i class="bi bi-heart" aria-hidden="true"></i>

        @if(($wishlistCount ?? 0) > 0)
            <span
                class="navbar-badge"
                aria-label="{{ $wishlistCount }} favoritos">

                {{ $wishlistCount }}

            </span>
        @endif

    </a>

    {{-- Carrito --}}
    <a
        href="{{ route('cart.index') }}"
        class="navbar-action"
        aria-label="Carrito de compras">

        <i class="bi bi-cart3" aria-hidden="true"></i>

        @if(($cartCount ?? 0) > 0)
            <span
                class="navbar-badge"
                aria-label="{{ $cartCount }} productos en el carrito">

                {{ $cartCount }}

            </span>
        @endif

    </a>

</div>