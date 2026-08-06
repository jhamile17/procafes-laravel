<div class="checkout-card">

    {{-- ==========================================================
        HEADER
    =========================================================== --}}

    <div class="checkout-card-header">

        <span class="checkout-card-badge">

            Paso 2

        </span>

        <h2 class="checkout-card-title">

            Método de pago

        </h2>

        <p class="checkout-card-subtitle">

            Selecciona cómo deseas pagar tu pedido.

        </p>

    </div>

    {{-- ==========================================================
        BODY
    =========================================================== --}}

    <div class="checkout-card-body">

        <div class="checkout-payment-options">

            {{-- ==================================================
                PAGO EN TIENDA
            =================================================== --}}

            <label class="checkout-payment-card">

                <input
                    id="paymentStore"
                    type="radio"
                    name="payment_method"
                    value="store"
                    checked>

                <div class="checkout-payment-content">

                    <div class="checkout-payment-icon">

                        <i class="bi bi-shop"></i>

                    </div>

                    <div>

                        <h5>

                            Pago en tienda

                        </h5>

                        <p>

                            Paga cuando recojas tu pedido.

                        </p>

                        <span>

                            Efectivo o tarjeta

                        </span>

                    </div>

                </div>

            </label>

            {{-- ==================================================
                MERCADO PAGO
            =================================================== --}}

            <label class="checkout-payment-card">

                <input
                    id="paymentMercadoPago"
                    type="radio"
                    name="payment_method"
                    value="mercadopago">

                <div class="checkout-payment-content">

                    <div class="checkout-payment-icon">

                        <i class="bi bi-credit-card"></i>

                    </div>

                    <div>

                        <h5>

                            Mercado Pago

                        </h5>

                        <p>

                            Paga online de forma segura.

                        </p>

                        <span>

                            Tarjetas, Yape, Plin y otros medios

                        </span>

                    </div>

                </div>

            </label>

        </div>

        {{-- ==========================================================
            PANEL PAGO EN TIENDA
        =========================================================== --}}

        <div
            id="paymentStorePanel"
            class="checkout-payment-panel">

            <div class="checkout-alert">

                <i class="bi bi-info-circle-fill"></i>

                <div>

                    <strong>

                        Pago en tienda

                    </strong>

                    <span>

                        Podrás pagar en efectivo o con tarjeta cuando recojas tu pedido.

                    </span>

                </div>

            </div>

        </div>

        {{-- ==========================================================
            PANEL MERCADO PAGO
        =========================================================== --}}

        <div
            id="paymentMercadoPagoPanel"
            class="checkout-payment-panel d-none">

            <div class="checkout-alert">

                <i class="bi bi-shield-check"></i>

                <div>

                    <strong>

                        Pago seguro con Mercado Pago

                    </strong>

                    <span>

                        Después de confirmar el pedido serás redirigido a Mercado Pago para completar el pago.

                    </span>

                </div>

            </div>

        </div>

    </div>

</div>