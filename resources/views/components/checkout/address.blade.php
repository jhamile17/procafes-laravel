<div class="customer-card checkout-card">

    <div class="customer-card-header">

        <div>

            <span class="customer-card-badge">
                Dirección
            </span>

            <h2 class="customer-card-title">
                Dirección de envío
            </h2>

            <p class="customer-card-subtitle">
                Esta será la dirección utilizada para enviar tus productos mediante la agencia de transporte.
            </p>

        </div>

    </div>

    <div class="customer-card-body">

        {{-- ==========================================================
            Sin dirección registrada
        =========================================================== --}}

        @unless($address)

            <div
                id="addressEmpty"
                class="checkout-address-empty">

                <div class="checkout-address-empty-icon">

                    <i class="bi bi-geo-alt-fill"></i>

                </div>

                <h3 class="checkout-address-empty-title">

                    No tienes una dirección registrada

                </h3>

                <p class="checkout-address-empty-description">

                    Para continuar con tu compra primero debes seleccionar una dirección de entrega.

                </p>

                <button
                    type="button"
                    id="btnNuevaDireccion"
                    class="btn btn-brand">

                    <i class="bi bi-search"></i>

                    <span>Buscar dirección</span>

                </button>

            </div>

        @endunless

        {{-- ==========================================================
            Dirección registrada
        =========================================================== --}}

        <div
            id="addressView"
            class="{{ $address ? '' : 'd-none' }}">

            <div class="checkout-address-preview">

                <div class="checkout-address-icon">

                    <i class="bi bi-geo-alt-fill"></i>

                </div>

                <div class="checkout-address-content">

                    <h6 id="addressDireccion">

                        {{ $address?->direccion }}

                    </h6>

                    <p id="addressUbicacion">

                        {{ $address?->distrito }},
                        {{ $address?->provincia }}

                    </p>

                    <small id="addressDepartamento">

                        {{ $address?->departamento }}

                    </small>

                </div>

            </div>

            <div class="checkout-address-actions">

                <button
                    type="button"
                    id="btnEditAddress"
                    class="customer-btn customer-btn-outline">

                    <i class="bi bi-pencil-square me-2"></i>

                    Editar dirección

                </button>

            </div>

        </div>

        {{-- ==========================================================
            Buscar dirección
        =========================================================== --}}

        <div
            id="addressForm"
            class="checkout-address-form d-none">

            <div class="customer-form-group">

                <label
                    for="addressSearch"
                    class="customer-label">

                    Buscar dirección

                </label>

                <input
                    type="text"
                    id="addressSearch"
                    class="customer-input"
                    placeholder="Ej. Av. Perú 123, Pichanaqui">

            </div>

            <div
                id="addressResults"
                class="checkout-address-results">

            </div>

            <div class="checkout-address-actions mt-3">

                <button
                    type="button"
                    id="btnCancelAddress"
                    class="customer-btn customer-btn-outline">

                    <i class="bi bi-arrow-left me-2"></i>

                    Cancelar

                </button>

            </div>

        </div>

        {{-- ==========================================================
            Campos ocultos
        =========================================================== --}}

        <input
            type="hidden"
            name="direccion"
            id="direccion"
            value="{{ $address?->direccion }}">

        <input
            type="hidden"
            name="departamento"
            id="departamento"
            value="{{ $address?->departamento }}">

        <input
            type="hidden"
            name="provincia"
            id="provincia"
            value="{{ $address?->provincia }}">

        <input
            type="hidden"
            name="distrito"
            id="distrito"
            value="{{ $address?->distrito }}">

        <input
            type="hidden"
            name="latitude"
            id="latitude"
            value="{{ $address?->latitude }}">

        <input
            type="hidden"
            name="longitude"
            id="longitude"
            value="{{ $address?->longitude }}">

    </div>

</div>
