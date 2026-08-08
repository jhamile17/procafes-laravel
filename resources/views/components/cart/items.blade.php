<div class="customer-card">

    <div class="customer-card-header">

        <div class="customer-card-heading">

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

        <div class="customer-card-actions">

            <button
                id="btnClearCart"
                type="button"
                class="cart-clear-btn d-none">

                <i class="bi bi-trash3 me-2"></i>
                Vaciar

            </button>

        </div>

    </div>

    <div class="customer-card-divider"></div>

    <div class="customer-card-body">

        <div id="cartItems">

            {{-- Render dinámico --}}

        </div>

    </div>

</div>