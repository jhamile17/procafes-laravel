{{-- ==========================================================
    FORMULARIO DIRECCIÓN
========================================================== --}}

<div
    id="addressForm"
    class="checkout-address-form d-none">

    {{-- ==========================================================
        HEADER
    =========================================================== --}}

    <div class="checkout-form-header">

        <h4>

            Dirección de entrega

        </h4>

        <p>

            Busca tu dirección y agrega una referencia para facilitar la entrega de tu pedido.

        </p>

    </div>

    {{-- ==========================================================
        BUSCADOR
    =========================================================== --}}

    <div class="customer-form-group">

        <label class="customer-label">

            Buscar dirección

        </label>

        <div class="checkout-search-box">

            <i class="bi bi-search"></i>

            <input
                type="text"
                id="addressSearch"
                class="customer-input"
                autocomplete="off"
                placeholder="Ej. Av. Perú 245, Pichanaqui">

        </div>

        <small class="customer-helper">

            Escribe al menos 2 caracteres para buscar una dirección.

        </small>

    </div>

    {{-- ==========================================================
        CARGANDO
    =========================================================== --}}

    <div
        id="addressLoading"
        class="checkout-search-loading d-none">

        <i class="bi bi-arrow-repeat"></i>

        <span>

            Buscando direcciones...

        </span>

    </div>

    {{-- ==========================================================
        RESULTADOS
    =========================================================== --}}

    <div
        id="addressResults"
        class="checkout-search-results">

    </div>

    {{-- ==========================================================
        REFERENCIA
    =========================================================== --}}

    <div class="customer-form-group">

        <label class="customer-label">

            Referencia

        </label>

        <textarea
            id="referencia"
            name="referencia"
            rows="3"
            class="customer-input"
            placeholder="Ej. Frente al mercado, portón negro, segundo piso...">{{ $address?->referencia }}</textarea>

    </div>

    {{-- ==========================================================
        CAMPOS OCULTOS
    =========================================================== --}}

    <input
        type="hidden"
        id="addressId"
        value="{{ $address?->id }}">

    <input
        type="hidden"
        id="csrfToken"
        value="{{ csrf_token() }}">

    <input
        type="hidden"
        id="direccion"
        name="direccion"
        value="{{ $address?->direccion }}">

    <input
        type="hidden"
        id="departamento"
        name="departamento"
        value="{{ $address?->departamento }}">

    <input
        type="hidden"
        id="provincia"
        name="provincia"
        value="{{ $address?->provincia }}">

    <input
        type="hidden"
        id="distrito"
        name="distrito"
        value="{{ $address?->distrito }}">

    <input
        type="hidden"
        id="latitude"
        name="latitude"
        value="{{ $address?->latitude }}">

    <input
        type="hidden"
        id="longitude"
        name="longitude"
        value="{{ $address?->longitude }}">

    {{-- ==========================================================
        MENSAJES
    =========================================================== --}}

    <div
        id="addressMessage"
        class="checkout-message">

    </div>

    {{-- ==========================================================
        ACCIONES
    =========================================================== --}}

    <div class="checkout-actions">

        <button
            type="button"
            id="btnCancelAddress"
            class="customer-btn customer-btn-secondary">

            <i class="bi bi-x-circle"></i>

            Cancelar

        </button>

        <button
            type="button"
            id="btnSaveAddress"
            class="customer-btn">

            <i class="bi bi-check-circle"></i>

            <span>

                Guardar dirección

            </span>

        </button>

    </div>

</div>