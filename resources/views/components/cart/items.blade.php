<div class="customer-card">

    <div class="customer-card-header">

        <div>

            <span class="customer-card-badge">

                Carrito

            </span>

            <h2 class="customer-card-title">

                Productos seleccionados

            </h2>

            <p class="customer-card-subtitle">

                Administra las cantidades o elimina productos antes de continuar con tu compra.

            </p>

        </div>

        <button
            id="btnClearCart"
            class="btn btn-outline-danger d-none">

            <i class="bi bi-trash me-2"></i>

            Vaciar carrito

        </button>

    </div>

    <div class="customer-card-body p-0">

        <div id="cartItems">

            {{-- render.js insertará aquí los productos --}}

        </div>

    </div>

</div>