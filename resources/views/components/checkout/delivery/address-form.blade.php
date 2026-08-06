{{-- ==========================================================
    FORMULARIO DIRECCIÓN DE ENVÍO
========================================================== --}}

<div
    id="addressForm"
    class="checkout-address-form d-none">

    {{-- ==========================================================
        HEADER
    =========================================================== --}}

    <div class="checkout-form-header">

        <h4>

            Dirección de envío

        </h4>

        <p>

            Busca tu dirección y agrega una referencia para facilitar la entrega.

        </p>

    </div>

    {{-- ==========================================================
        BUSCADOR
    =========================================================== --}}

    <div class="customer-form-group">

        <label
            for="addressSearch"
            class="customer-label">

            Buscar dirección

        </label>

        <div class="checkout-search-box">

            <i class="bi bi-search"></i>

            <input
                type="text"
                id="addressSearch"
                class="customer-input"
                placeholder="Ej. Av. Perú 245">

        </div>

    </div>

    {{-- ==========================================================
        RESULTADOS
    =========================================================== --}}
        <div
            id="addressResults"
            class="checkout-search-results">

        </div>

        <div class="customer-form-group">

        <label
            for="numero"
            class="customer-label">

            Número / Interior

        </label>

        <input
            type="text"
            id="numero"
            class="customer-input"
            maxlength="60"
            placeholder="Ej. 245, Mz. B Lt. 12, Interior 201"
            value="{{ $address?->numero }}">

    </div>

    {{-- ==========================================================
        REFERENCIA
    =========================================================== --}}

    <div class="customer-form-group">

        <label
            for="referencia"
            class="customer-label">

            Referencia

        </label>

        <textarea
            id="referencia"
            class="customer-input"
            rows="3"
            placeholder="Ej. Frente al mercado, portón negro, segundo piso...">{{ $address?->referencia }}</textarea>

    </div>

    {{-- ==========================================================
        CAMPOS OCULTOS
    =========================================================== --}}

    <input
        type="hidden"
        id="direccion"
        value="{{ $address?->direccion }}">

    <input
        type="hidden"
        id="departamento"
        value="{{ $address?->departamento }}">

    <input
        type="hidden"
        id="provincia"
        value="{{ $address?->provincia }}">

    <input
        type="hidden"
        id="distrito"
        value="{{ $address?->distrito }}">

    <input
        type="hidden"
        id="latitude"
        value="{{ $address?->latitude }}">

    <input
        type="hidden"
        id="longitude"
        value="{{ $address?->longitude }}">

    {{-- ==========================================================
        BOTONES
    =========================================================== --}}

    <div class="checkout-actions">

        <button
            type="button"
            id="btnCancelAddress"
            class="customer-btn customer-btn-secondary">

            <i class="bi bi-arrow-left"></i>

            Cancelar

        </button>

        <button
            type="button"
            id="btnSaveAddress"
            class="customer-btn">

            <i class="bi bi-check-circle"></i>

            Guardar dirección

        </button>

    </div>

</div>