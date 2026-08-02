<div class="customer-card checkout-summary sticky-top">

    <div class="customer-card-header">

        <div>

            <span class="customer-card-badge">
                Resumen
            </span>

            <h2 class="customer-card-title">
                Tu pedido
            </h2>

            <p class="customer-card-subtitle">
                Revisa el detalle antes de confirmar tu compra.
            </p>

        </div>

    </div>

    <div class="customer-card-body">

        {{-- ==========================================================
            Productos
        =========================================================== --}}

        <div class="checkout-summary-products">

            @foreach($items as $item)

                <div class="checkout-summary-item">

                    <div class="checkout-summary-image">

                        <img
                            src="{{ $item->product->image_url }}"
                            alt="{{ $item->product->name }}">

                    </div>

                    <div class="checkout-summary-info">

                        <h6>

                            {{ $item->product->name }}

                        </h6>

                        <small>

                            {{ $item->quantity }}
                            ×
                            S/
                            {{ number_format($item->unit_price,2) }}

                        </small>

                    </div>

                    <strong>

                        S/
                        {{ number_format($item->subtotal,2) }}

                    </strong>

                </div>

            @endforeach

        </div>

        <hr>

        {{-- ==========================================================
            Totales
        =========================================================== --}}

        <div class="checkout-summary-row">

            <span>
                Productos
            </span>

            <strong>

                {{ $cantidad }}

            </strong>

        </div>

        <div class="checkout-summary-row">

            <span>
                Subtotal
            </span>

            <strong>

                S/
                {{ number_format($subtotal,2) }}

            </strong>

        </div>

        <div class="checkout-summary-row">

            <span>
                IGV (18%)
            </span>

            <strong>

                S/
                {{ number_format($igv,2) }}

            </strong>

        </div>

        <hr>

        <div class="checkout-summary-total">

            <span>

                Total

            </span>

            <strong>

                S/
                {{ number_format($total,2) }}

            </strong>

        </div>

        {{-- ==========================================================
            Información
        =========================================================== --}}

        <div class="customer-notice customer-notice-info mt-4">

            <div class="customer-notice-icon">

                <i class="bi bi-shield-check"></i>

            </div>

            <div class="customer-notice-content">

                <h6>

                    Compra protegida

                </h6>

                <p>

                    Tu información personal y el proceso de pago están protegidos durante toda la compra.

                </p>

            </div>

        </div>

        {{-- ==========================================================
            Acción
        =========================================================== --}}

        <button
            id="checkoutSubmitButton"
            type="submit"
            class="btn btn-brand btn-block btn-lg">

            <span class="checkout-button-icon">

                <i class="bi bi-bag-check-fill"></i>

            </span>

            <span class="checkout-button-text">

                Confirmar pedido

            </span>

        </button>

    </div>

</div>
