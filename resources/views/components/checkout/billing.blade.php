<div class="customer-card checkout-card mt-4">

    <div class="customer-card-header">

        <div>

            <span class="customer-card-badge">

                Comprobante

            </span>

            <h2 class="customer-card-title">

                Comprobante electrónico

            </h2>

            <p class="customer-card-subtitle">

                Selecciona el comprobante que deseas recibir por tu compra.

            </p>

        </div>

    </div>

    <div class="customer-card-body">

        {{-- ==========================================================
            BOLETA
        =========================================================== --}}

        <label class="checkout-billing-option">

            <input
                type="radio"
                name="billing_type"
                value="BOLETA"
                class="checkout-billing-radio"
                checked>

            <div class="checkout-billing-content">

                <div class="checkout-billing-icon">

                    <i class="bi bi-receipt"></i>

                </div>

                <div class="checkout-billing-info">

                    <h6>

                        Boleta electrónica

                    </h6>

                    <p>

                        Se emitirá utilizando los datos de tu cuenta.

                    </p>

                </div>

            </div>

        </label>

        {{-- ==========================================================
            FACTURA
        =========================================================== --}}

        <label class="checkout-billing-option">

            <input
                type="radio"
                name="billing_type"
                value="FACTURA"
                class="checkout-billing-radio">

            <div class="checkout-billing-content">

                <div class="checkout-billing-icon">

                    <i class="bi bi-building"></i>

                </div>

                <div class="checkout-billing-info">

                    <h6>

                        Factura electrónica

                    </h6>

                    <p>

                        Selecciona una empresa registrada.

                    </p>

                </div>

            </div>

        </label>

        {{-- ==========================================================
            EMPRESAS REGISTRADAS
        =========================================================== --}}

        <div
            id="billingProfiles"
            class="checkout-billing-profiles d-none">

            <div class="customer-form-group">

                <label
                    for="billing_profile_id"
                    class="customer-label">

                    Empresa

                </label>

                <select
                    id="billing_profile_id"
                    name="billing_profile_id"
                    class="customer-select">

                    <option value="">

                        Selecciona una empresa

                    </option>

                    @foreach($billingProfiles as $profile)

                        <option
                            value="{{ $profile->id }}"
                            {{ $profile->predeterminado ? 'selected' : '' }}>

                            {{ $profile->alias }}
                            - {{ $profile->ruc }}

                        </option>

                    @endforeach

                </select>

            </div>

            <div class="checkout-billing-actions">

                <button
                    type="button"
                    id="btnNewBillingProfile"
                    class="btn btn-outline">

                    <i class="bi bi-plus-circle"></i>

                    Registrar empresa

                </button>

            </div>

        </div>

        {{-- ==========================================================
            NUEVA EMPRESA
        =========================================================== --}}

        <div
            id="billingForm"
            class="checkout-billing-form d-none">

            {{-- RUC --}}

            <div class="customer-form-group">

                <label
                    for="billingRuc"
                    class="customer-label">

                    RUC

                    <span class="text-danger">*</span>

                </label>

                <input
                    type="text"
                    id="billingRuc"
                    maxlength="11"
                    autocomplete="off"
                    class="customer-input"
                    placeholder="Ingrese el RUC">

                <small
                    id="billingLoading"
                    class="checkout-billing-loading d-none">

                    <i class="bi bi-arrow-repeat"></i>

                    Consultando SUNAT...

                </small>

                <small
                    id="billingMessage"
                    class="checkout-billing-message">

                </small>

            </div>

            {{-- Razón Social --}}

            <div class="customer-form-group">

                <label
                    for="billingRazonSocial"
                    class="customer-label">

                    Razón social

                </label>

                <input
                    type="text"
                    id="billingRazonSocial"
                    class="customer-input"
                    readonly>

            </div>

            {{-- Dirección Fiscal --}}

            <div class="customer-form-group">

                <label
                    for="billingDireccion"
                    class="customer-label">

                    Dirección fiscal

                </label>

                <input
                    type="text"
                    id="billingDireccion"
                    class="customer-input"
                    readonly>

            </div>

            {{-- Alias --}}

            <div class="customer-form-group">

                <label
                    for="billingAlias"
                    class="customer-label">

                    Nombre para identificar esta empresa

                </label>

                <input
                    type="text"
                    id="billingAlias"
                    class="customer-input"
                    maxlength="100"
                    placeholder="Ej. Trabajo, Oficina, Empresa Principal">

            </div>

            {{-- Acciones --}}

            <div class="checkout-billing-form-actions">

                <button
                    type="button"
                    id="btnCancelBillingForm"
                    class="btn btn-outline">

                    Cancelar

                </button>

                <button
                    type="button"
                    id="btnSaveBillingProfile"
                    class="btn btn-brand">

                    <i class="bi bi-building-add"></i>

                    Guardar empresa

                </button>

            </div>

        </div>

    </div>

</div>