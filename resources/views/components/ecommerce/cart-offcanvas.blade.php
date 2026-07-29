<div
    class="offcanvas offcanvas-end"
    tabindex="-1"
    id="cartOffcanvas"
    aria-labelledby="cartOffcanvasLabel">

    <div class="offcanvas-header">

        <h5
            class="offcanvas-title"
            id="cartOffcanvasLabel">

            <i class="bi bi-cart3 me-2"></i>
            Mi carrito

        </h5>

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="offcanvas"
            aria-label="Cerrar">
        </button>

    </div>

    <div class="offcanvas-body d-flex flex-column">

        {{-- Estado de carga --}}
        <div
            id="cartLoading"
            class="text-center py-5 d-none">

            <div
                class="spinner-border text-dark"
                role="status">
            </div>

            <p class="mt-3 mb-0 text-muted">
                Cargando carrito...
            </p>

        </div>

        {{-- Productos --}}
        <div
            id="cartItems"
            class="list-group list-group-flush flex-grow-1">
        </div>

        {{-- Carrito vacío --}}
        <div
            id="cartEmpty"
            class="text-center py-5 d-none">

            <i class="bi bi-cart-x fs-1 text-muted"></i>

            <h5 class="mt-3">
                Tu carrito está vacío
            </h5>

            <p class="text-muted mb-0">
                Agrega algunos productos para comenzar tu compra.
            </p>

        </div>

        {{-- Resumen --}}
        <div
            id="cartSummary"
            class="border-top pt-3 mt-3">

            <div class="d-flex justify-content-between mb-3">

                <strong>Total</strong>

                <strong id="cartTotal">
                    S/ 0.00
                </strong>

            </div>

            <div class="d-grid gap-2">

                <a
                    href="{{ route('cart.index') }}"
                    class="btn btn-outline-dark">

                    Ver carrito

                </a>

                @auth

                    <a
                        href="{{ route('checkout') }}"
                        class="btn btn-dark">

                        Finalizar compra

                    </a>

                @else

                    <a
                        href="{{ route('login') }}"
                        class="btn btn-dark">

                        Iniciar sesión para pagar

                    </a>

                @endauth

                <button
                    id="btnClearCart"
                    type="button"
                    class="btn btn-outline-danger">

                    Vaciar carrito

                </button>

            </div>

        </div>

    </div>

</div>

<div
    id="toastContainer"
    class="toast-container position-fixed bottom-0 end-0 p-3"
    style="z-index:1100">
</div>