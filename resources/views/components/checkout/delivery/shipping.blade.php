{{-- ==========================================================
    DIRECCIÓN DE ENVÍO
========================================================== --}}

<div
    id="deliveryPanel"
    class="checkout-shipping d-none">

    {{-- ==========================================================
        DIRECCIÓN REGISTRADA
    =========================================================== --}}

    <div
        id="addressView"
        class="{{ $address ? '' : 'd-none' }}">

        <div class="checkout-shipping-card">

            <div class="checkout-shipping-header">

                <div class="checkout-shipping-content">

                    <span class="checkout-shipping-badge">

                        Dirección de envío

                    </span>

                    <div
                        id="addressTitle"
                        class="checkout-shipping-title">

                        {{ $address?->direccion }}

                    </div>

                    <p id="addressLocation">

                        {{ $address?->distrito }},
                        {{ $address?->provincia }}

                    </p>

                    <small
                        id="addressReference"
                        class="{{ empty($address?->referencia) ? 'd-none' : '' }}">

                        Referencia:
                        {{ $address?->referencia }}

                    </small>

                </div>

                <button
                    type="button"
                    id="btnEditAddress"
                    class="customer-btn">

                    <i class="bi bi-pencil"></i>

                    Editar

                </button>

            </div>

        </div>

    </div>

    {{-- ==========================================================
        SIN DIRECCIÓN
    =========================================================== --}}

    <div
        id="addressEmpty"
        class="{{ $address ? 'd-none' : '' }}">

        <div class="checkout-empty-address">

            <i class="bi bi-geo-alt"></i>

            <h4>

                Dirección de envío

            </h4>

            <p>

                No tienes una dirección registrada.

            </p>

            <button
                type="button"
                id="btnAddAddress"
                class="customer-btn">

                <i class="bi bi-plus-circle"></i>

                Agregar dirección

            </button>

        </div>

    </div>

</div>