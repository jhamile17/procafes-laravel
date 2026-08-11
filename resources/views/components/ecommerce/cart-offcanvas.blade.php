<div
    class="offcanvas offcanvas-end"
    tabindex="-1"
    id="cartOffcanvas"
    aria-labelledby="cartOffcanvasLabel">

  {{-- Header --}}
<div class="offcanvas-header">

    <div>

        <h5 id="cartOffcanvasLabel">
            Mi carrito
        </h5>

        <small id="cartCountLabel">
            0 productos
        </small>

    </div>

    <button
        class="btn-close"
        data-bs-dismiss="offcanvas">
    </button>

</div>

{{-- Mensajes --}}
<div
    id="cartMessage"
    class="alert alert-warning d-none">

    <div class="cart-warning-icon">
        <i class="bi bi-exclamation-triangle-fill"></i>
    </div>
   <div>

        <strong>Límite alcanzado</strong>

        <div id="cartMessageText"></div>

    </div>

</div>

{{-- Body --}}
<div class="offcanvas-body p-0 d-flex flex-column">

    <div
        id="cartLoading"
        class="d-none flex-grow-1 d-flex align-items-center justify-content-center">

        <div class="text-center">

            <div class="spinner-border text-danger"></div>

            <p class="small mt-3">
                Cargando...
            </p>

        </div>

    </div>

    <div
        id="cartEmpty"
        class="d-none flex-grow-1 d-flex align-items-center justify-content-center">

        <div class="text-center">

            <i class="bi bi-cart-x fs-1 text-secondary"></i>

            <h6 class="mt-3">

                Tu carrito está vacío

            </h6>

            <small class="text-muted">

                Agrega tus bebidas favoritas.

            </small>

        </div>

    </div>

    <div
        id="cartItems"
        class="flex-grow-1 overflow-auto">

    </div>

</div>

{{-- Footer --}}
<div class="border-top bg-white p-3">

    <div class="d-flex justify-content-between align-items-center mb-3">

        <span class="text-muted">

            Total a pagar 

        </span>

        <span id="cartTotal" class="fw-bold mb-1">

            S/ 0.00

        </span>

    </div>

    <div class="d-grid gap-1">

        <a
            href="{{ route('cart.index') }}"
            class="btn btn-outline-dark">

            <i class="bi bi-cart3 me-2"></i>

            Ver carrito

        </a>

        @auth

            <a
                href="{{ route('checkout.index') }}"
                class="btn btn-danger w-100 cart-action-btn">

                <i class="bi bi-credit-card me-2"></i>

                Finalizar compra

            </a>

        @else

            <a
                href="{{ route('login') }}"
                class="btn btn-danger">

                <i class="bi bi-box-arrow-in-right me-2"></i>

                Iniciar sesión

            </a>

        @endauth

        <button
            id="btnClearCart"
            class="btn btn-link text-danger">

            <i class="bi bi-trash3 me-1"></i>

            Vaciar carrito

        </button>

    </div>

</div>