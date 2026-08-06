<div class="checkout-delivery-options">
    <label class="checkout-delivery-card">
        <input
            type="radio"
            id="deliveryPickup"
            name="delivery_type"
            value="pickup"
            checked>
        <div class="checkout-delivery-content">
            <div class="checkout-delivery-icon">
                <i class="bi bi-shop"></i>

            </div>

            <div class="checkout-delivery-info">

                <h5>

                    Recojo en tienda

                </h5>

                <p>

                    Retira tu pedido directamente en nuestro local.

                </p>

            </div>

        </div>

    </label>

    {{-- ==========================================================
        DELIVERY LOCAL
    =========================================================== --}}

    <label class="checkout-delivery-card">

        <input
            type="radio"
            id="deliveryShipping"
            name="delivery_type"
            value="delivery">

        <div class="checkout-delivery-content">

            <div class="checkout-delivery-icon">

                <i class="bi bi-truck"></i>

            </div>

            <div class="checkout-delivery-info">

                <h5>

                    Delivery local

                </h5>

                <p>

                    Recibe tu pedido en la dirección registrada.

                </p>

            </div>

        </div>

    </label>

</div>