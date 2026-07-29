<div class="customer-card cart-summary">

    <div class="customer-card-body">

        <span class="customer-card-badge">

            Resumen

        </span>

        <h3 class="customer-card-title">

            Resumen del pedido

        </h3>

        <p class="customer-card-subtitle">

            Verifica el importe total antes de finalizar la compra.

        </p>

        <div class="cart-summary-total">

            <span>Total</span>

            <strong id="cartTotal">

                S/ 0.00

            </strong>

        </div>

        <div class="d-grid gap-2 mt-4">

            <a
                href="{{ route('checkout') }}"
                class="btn btn-primary">

                <i class="bi bi-credit-card me-2"></i>

                Continuar compra

            </a>

            <a
                href="{{ route('products') }}"
                class="btn btn-outline-secondary">

                <i class="bi bi-arrow-left me-2"></i>

                Seguir comprando

            </a>

        </div>

    </div>

</div>