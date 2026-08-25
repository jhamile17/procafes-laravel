{{--========================================================================== 
    NAVBAR ACTIONS
==========================================================================--}}

<div class="navbar-actions">

    {{-- =========================================================
        FAVORITOS
    ========================================================== --}}

    <a
        href="{{ route('customer.wishlist') }}"
        class="navbar-action"
        aria-label="Lista de favoritos"
    >

        <i
            class="bi bi-heart"
            aria-hidden="true"
        ></i>


        <span
            id="wishlistBadge"
            class="navbar-badge"
            style="display:none;"
        >
            0
        </span>

    </a>


    {{-- =========================================================
        CARRITO
    ========================================================== --}}

    <a
        href="{{ route('cart.index') }}"
        class="navbar-action"
        aria-label="Carrito de compras"
    >

        <i
            class="bi bi-cart3"
            aria-hidden="true"
        ></i>


        <span
            id="cartBadge"
            class="navbar-badge"
            style="{{ ($cartCount ?? 0) > 0 ? '' : 'display:none;' }}"
            aria-label="{{ $cartCount ?? 0 }} productos en el carrito"
        >
            {{ $cartCount ?? 0 }}
        </span>

    </a>

</div>