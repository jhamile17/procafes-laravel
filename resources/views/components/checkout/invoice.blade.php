<div class="checkout-card">

    {{-- ==========================================================
        HEADER
    =========================================================== --}}

    <div class="checkout-card-header">

        <span class="checkout-card-badge">

            Comprobante

        </span>

        <h2 class="checkout-card-title">

            Datos del comprobante

        </h2>

        <p class="checkout-card-subtitle">

            Selecciona el tipo de comprobante. Los datos se completarán automáticamente al ingresar el documento.

        </p>

    </div>

    {{-- ==========================================================
        BODY
    =========================================================== --}}

    <div class="checkout-card-body">

        {{-- ==========================================================
            TIPO DE COMPROBANTE
        =========================================================== --}}

        <div class="checkout-document-options">

            {{-- BOLETA --}}

            <label class="checkout-document-card">

                <input
                    type="radio"
                    id="boleta"
                    name="tipo_comprobante"
                    value="BOLETA"
                    required
                    {{ old('tipo_comprobante', 'BOLETA') === 'BOLETA' ? 'checked' : '' }}>

                <div class="checkout-document-content">

                    <div class="checkout-document-icon">

                        <i class="bi bi-receipt"></i>

                    </div>

                    <div class="checkout-document-info">

                        <h5>

                            Boleta

                        </h5>

                        <p>

                            Documento para consumidores finales.

                        </p>

                    </div>

                </div>

            </label>

            {{-- FACTURA --}}

            <label class="checkout-document-card">

                <input
                    type="radio"
                    id="factura"
                    name="tipo_comprobante"
                    value="FACTURA"
                    required
                    {{ old('tipo_comprobante') === 'FACTURA' ? 'checked' : '' }}>

                <div class="checkout-document-content">

                    <div class="checkout-document-icon">

                        <i class="bi bi-building"></i>

                    </div>

                    <div class="checkout-document-info">

                        <h5>

                            Factura

                        </h5>

                        <p>

                            Documento para empresas con RUC.

                        </p>

                    </div>

                </div>

            </label>

        </div>

        {{-- ==========================================================
            DOCUMENTO
        =========================================================== --}}

        <input
            type="hidden"
            id="tipo_documento"
            name="tipo_documento"
            value="{{ old('tipo_documento', 'DNI') }}">

        <div class="checkout-document-fields">

            {{-- DOCUMENTO --}}

            <div class="customer-field">

                <label
                    id="documentLabel"
                    class="customer-label">

                    DNI

                </label>

                <input
                    type="text"
                    id="numero_documento"
                    name="numero_documento"
                    class="customer-input"
                    value="{{ old('numero_documento') }}"
                    placeholder="Ingrese su DNI"
                    maxlength="11"
                    autocomplete="off"
                    required>

                @error('numero_documento')

                    <small class="text-danger">

                        {{ $message }}

                    </small>

                @enderror

            </div>

            {{-- ESTADO --}}

            <p
                id="documentStatus"
                class="checkout-document-status"
                aria-live="polite">

            </p>

            {{-- ==========================================================
                BOLETA
            =========================================================== --}}

            <div id="boletaFields">

                <div class="customer-field">

                    <label class="customer-label">

                        Nombre completo

                    </label>

                    <input
                        type="text"
                        id="nombre"
                        name="nombre"
                        class="customer-input"
                        value="{{ old('nombre') }}"
                        placeholder="Se completará automáticamente"
                        readonly>

                    @error('nombre')

                        <small class="text-danger">

                            {{ $message }}

                        </small>

                    @enderror

                </div>

            </div>

            {{-- ==========================================================
                FACTURA
            =========================================================== --}}

            <div
                id="facturaFields"
                class="d-none">

                <div class="customer-field">

                    <label class="customer-label">

                        Razón social

                    </label>

                    <input
                        type="text"
                        id="razon_social"
                        name="razon_social"
                        class="customer-input"
                        value="{{ old('razon_social') }}"
                        placeholder="Se completará automáticamente"
                        readonly>

                    @error('razon_social')

                        <small class="text-danger">

                            {{ $message }}

                        </small>

                    @enderror

                </div>

                <div class="customer-field">

                    <label class="customer-label">

                        Dirección fiscal

                    </label>

                    <input
                        type="text"
                        id="direccion_fiscal"
                        name="direccion_fiscal"
                        class="customer-input"
                        value="{{ old('direccion_fiscal') }}"
                        placeholder="Se completará automáticamente"
                        readonly>

                    @error('direccion_fiscal')

                        <small class="text-danger">

                            {{ $message }}

                        </small>

                    @enderror

                </div>

            </div>

        </div>

    </div>

</div>