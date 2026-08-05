{{-- ==========================================================
    OPCIONES DE ENTREGA
========================================================== --}}

<div class="checkout-delivery-options">

    {{-- ==========================================================
        RECOJO EN TIENDA
    =========================================================== --}}

    <label class="checkout-delivery-card">

        <input
            type="radio"
            name="delivery_type"
            value="pickup"
            id="deliveryPickup"
            checked>

        <div class="checkout-delivery-content">

            <div class="checkout-delivery-icon">

                <i class="bi bi-shop"></i>

            </div>

            <div class="checkout-delivery-info">

                <h5>

                    Recojo en tienda

                </h5>

                <span>

                    Sin costo

                </span>

            </div>

        </div>

    </label>

    {{-- ==========================================================
        ENVÍO
    =========================================================== --}}

    @if($permiteEnvio)

        <label class="checkout-delivery-card">

            <input
                type="radio"
                name="delivery_type"
                value="delivery"
                id="deliveryShipping">

            <div class="checkout-delivery-content">

                <div class="checkout-delivery-icon">

                    <i class="bi bi-truck"></i>

                </div>

                <div class="checkout-delivery-info">

                    <h5>

                        Envío

                    </h5>

                    <span>

                        Transporte coordinado

                    </span>

                </div>

            </div>

        </label>

    @endif

</div>