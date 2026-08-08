{{--==========================================================================
    NAVBAR ACTIONS
==========================================================================--}}

<div class="navbar-actions">

    {{-- Favoritos --}}
    <a
        href="{{ route('customer.wishlist') }}"        class="navbar-action"
        aria-label="Lista de favoritos">

        <i class="bi bi-heart" aria-hidden="true"></i>

        <span
            id="wishlistBadge"
            class="navbar-badge"
            style="display:none;">

            0

        </span>

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