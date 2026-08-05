{{-- ==========================================================
    PANEL ENVÍO
========================================================== --}}

<div
    id="deliveryPanel"
    class="checkout-panel d-none">

    {{-- ==========================================================
        DIRECCIÓN REGISTRADA
    =========================================================== --}}

    @if($address)

        <div
            id="addressView"
            class="checkout-address">

            <div class="checkout-address-header">

                <div class="checkout-address-icon">

                    <i class="bi bi-geo-alt-fill"></i>

                </div>

                <div class="checkout-address-content">

                    <span class="checkout-address-badge">

                        Dirección de envío

                    </span>

                    <h4>

                        {{ $address->direccion }}

                    </h4>

                    <p>

                        {{ $address->distrito }},
                        {{ $address->provincia }}

                    </p>

                    <small>

                        {{ $address->departamento }}

                    </small>

                </div>

                <button
                    type="button"
                    id="btnEditAddress"
                    class="customer-btn customer-btn-sm">

                    <i class="bi bi-pencil-square"></i>

                    Editar

                </button>

            </div>

            <div class="checkout-delivery-note">

                <i class="bi bi-whatsapp"></i>

                <span>

                    El costo del transporte será coordinado por WhatsApp
                    después de confirmar tu compra.

                </span>

            </div>

        </div>

    @else

        {{-- ==========================================================
            SIN DIRECCIÓN
        =========================================================== --}}

        <div
            id="addressEmpty"
            class="checkout-empty">

            <div class="checkout-empty-icon">

                <i class="bi bi-geo-alt"></i>

            </div>

            <h4>

                No tienes una dirección registrada

            </h4>

            <p>

                Para realizar el envío primero debes registrar una dirección.

            </p>

            <button
                type="button"
                id="btnAddAddress"
                class="customer-button">

                <i class="bi bi-search"></i>

                Buscar dirección

            </button>

        </div>

    @endif

</div>