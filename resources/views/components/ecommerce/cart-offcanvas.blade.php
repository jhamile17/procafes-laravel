<div
    class="offcanvas offcanvas-end"
    tabindex="-1"
    id="cartOffcanvas"
    aria-labelledby="cartOffcanvasLabel">

    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="cartOffcanvasLabel">
            Mi carrito
        </h5>

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="offcanvas"
            aria-label="Cerrar"></button>
    </div>

    <div class="offcanvas-body d-flex flex-column">
        <div id="cartItems" class="list-group list-group-flush mb-3"></div>

        <div class="mt-auto border-top pt-3">
            <div class="d-flex justify-content-between fw-bold mb-3">
                <span>Total</span>
                <span id="cartTotal">S/ 0.00</span>
            </div>

            <div class="d-grid gap-2">
                @auth
                    <a href="{{ route('checkout') }}" class="btn btn-dark">
                        Ir a pagar
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-dark">
                        Iniciar sesión para pagar
                    </a>
                @endauth

                <button id="btnClearCart" type="button" class="btn btn-outline-danger">
                    Vaciar carrito
                </button>
            </div>
        </div>
    </div>
</div>

<div
    id="toastContainer"
    class="toast-container position-fixed bottom-0 end-0 p-3"
    style="z-index: 1100">
</div>