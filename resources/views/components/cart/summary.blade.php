<div class="customer-card">

    <div class="customer-card-body">

    <span class="customer-card-badge">
        Resumen
    </span>

    <h3 class="customer-card-title">
        Resumen del pedido
    </h3>

    <p class="customer-card-subtitle">
        El monto mostrado incluye todos los impuestos aplicables.
    </p>

    <div class="cart-summary-total">

        <span>Total a pagar</span>

        <strong id="cartPageTotal">
            S/ 0.00
        </strong>

    </div>

    <small class="cart-summary-note">
        Precio final con IGV incluido.
    </small>

    <div class="d-grid gap-3 mt-4">

        <a
            href="{{ route('checkout.index') }}"
            class="customer-btn">

            <i class="bi bi-bag-check-fill me-2"></i>

            Continuar compra

        </a>

        <a
            href="{{ route('products') }}"
            class="customer-btn-secondary">

            <i class="bi bi-arrow-left me-2"></i>

            Seguir comprando

        </a>

    </div>

</div>
