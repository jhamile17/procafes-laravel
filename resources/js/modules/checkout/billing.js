/*
|--------------------------------------------------------------------------
| BILLING
|--------------------------------------------------------------------------
*/

document.addEventListener('DOMContentLoaded', () => {

    /*
    |--------------------------------------------------------------------------
    | Elementos
    |--------------------------------------------------------------------------
    */

    const checkoutForm = document.getElementById('checkoutForm');

    if (!checkoutForm) {
        return;
    }

    const billingRadios = document.querySelectorAll(
        'input[name="billing_type"]'
    );

    const billingProfiles = document.getElementById(
        'billingProfiles'
    );

    const billingForm = document.getElementById(
        'billingForm'
    );

    const billingProfile = document.getElementById(
        'billing_profile_id'
    );

    const btnNewBillingProfile = document.getElementById(
        'btnNewBillingProfile'
    );

    const btnCancelBillingForm = document.getElementById(
        'btnCancelBillingForm'
    );

    const btnSaveBillingProfile = document.getElementById(
        'btnSaveBillingProfile'
    );

    const billingRuc = document.getElementById(
        'billingRuc'
    );

    const billingAlias = document.getElementById(
        'billingAlias'
    );

    const billingRazonSocial = document.getElementById(
        'billingRazonSocial'
    );

    const billingDireccion = document.getElementById(
        'billingDireccion'
    );

    const billingLoading = document.getElementById(
        'billingLoading'
    );

    const billingMessage = document.getElementById(
        'billingMessage'
    );

    /*
    |--------------------------------------------------------------------------
    | Rutas
    |--------------------------------------------------------------------------
    */

    const routes = {

        store: checkoutForm.dataset.billingStore,

        searchRuc: checkoutForm.dataset.billingSearchRuc,

    };

    /*
    |--------------------------------------------------------------------------
    | CSRF
    |--------------------------------------------------------------------------
    */

    const csrfToken = document.querySelector(
        'meta[name="csrf-token"]'
    ).content;

    /*
    |--------------------------------------------------------------------------
    | Eventos
    |--------------------------------------------------------------------------
    */

    billingRadios.forEach(radio => {

        radio.addEventListener(
            'change',
            toggleBillingType
        );

    });

    btnNewBillingProfile?.addEventListener(
        'click',
        openBillingForm
    );

    btnCancelBillingForm?.addEventListener(
        'click',
        closeBillingForm
    );

    btnSaveBillingProfile?.addEventListener(
        'click',
        saveBillingProfile
    );

    /*
    |--------------------------------------------------------------------------
    | Buscar RUC automáticamente
    |--------------------------------------------------------------------------
    */

    let timer = null;

    billingRuc?.addEventListener(
        'input',
        () => {

            clearTimeout(timer);

            clearMessage();

            billingRazonSocial.value = '';

            billingDireccion.value = '';

            const ruc = billingRuc.value.trim();

            if (ruc.length !== 11) {

                hideLoading();

                return;

            }

            timer = setTimeout(() => {

                searchRuc(ruc);

            }, 500);

        }
    );

    /*
    |--------------------------------------------------------------------------
    | Tipo de comprobante
    |--------------------------------------------------------------------------
    */

    function toggleBillingType()
    {
        const selected = document.querySelector(
            'input[name="billing_type"]:checked'
        );

        const factura =
            selected?.value === 'FACTURA';

        billingProfiles.classList.toggle(
            'd-none',
            !factura
        );

        if (!factura) {

            closeBillingForm();

            billingProfile.value = '';

        }
    }

    /*
    |--------------------------------------------------------------------------
    | Mostrar formulario
    |--------------------------------------------------------------------------
    */

    function openBillingForm()
    {
        billingForm.classList.remove(
            'd-none'
        );

        billingRuc.focus();
    }

    /*
    |--------------------------------------------------------------------------
    | Ocultar formulario
    |--------------------------------------------------------------------------
    */

    function closeBillingForm()
    {
        clearForm();

        billingForm.classList.add(
            'd-none'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Consultar RUC
    |--------------------------------------------------------------------------
    */

    async function searchRuc(ruc)
    {
        showLoading();

        try {

            const response = await fetch(

                `${routes.searchRuc}?ruc=${ruc}`,

                {

                    headers: {

                        Accept: 'application/json',

                    },

                }

            );

            const result = await response.json();

            if (!response.ok) {

                throw new Error(
                    result.message
                );

            }

            billingRazonSocial.value =
                result.data.razon_social;

            billingDireccion.value =
                result.data.direccion_fiscal;

            showSuccess(
                'Empresa encontrada.'
            );

        }

        catch (error) {

            showError(
                error.message
            );

        }

        finally {

            hideLoading();

        }

    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    function clearForm()
    {
        billingRuc.value = '';

        billingAlias.value = '';

        billingRazonSocial.value = '';

        billingDireccion.value = '';

        clearMessage();
    }

    function showLoading()
    {
        billingLoading.classList.remove(
            'd-none'
        );
    }

    function hideLoading()
    {
        billingLoading.classList.add(
            'd-none'
        );
    }

    function showSuccess(message)
    {
        billingMessage.textContent = message;

        billingMessage.className =
            'checkout-billing-message success';
    }

    function showError(message)
    {
        billingMessage.textContent = message;

        billingMessage.className =
            'checkout-billing-message error';
    }

    function clearMessage()
    {
        billingMessage.textContent = '';

        billingMessage.className =
            'checkout-billing-message';
    }
    /*
    |--------------------------------------------------------------------------
    | Registrar empresa
    |--------------------------------------------------------------------------
    */

    async function saveBillingProfile()
    {
        clearMessage();

        if (!validateForm()) {
            return;
        }

        btnSaveBillingProfile.disabled = true;

        try {

            const response = await fetch(
                routes.store,
                {

                    method: 'POST',

                    headers: {

                        'Content-Type': 'application/json',

                        'Accept': 'application/json',

                        'X-CSRF-TOKEN': csrfToken,

                    },

                    body: JSON.stringify({

                        alias: billingAlias.value.trim(),

                        ruc: billingRuc.value.trim(),

                        razon_social: billingRazonSocial.value.trim(),

                        direccion_fiscal: billingDireccion.value.trim(),

                    }),

                }
            );

            const result = await response.json();

            if (!response.ok) {

                throw new Error(
                    result.message ??
                    'No fue posible registrar la empresa.'
                );

            }

            appendBillingProfile(
                result.data
            );

            showSuccess(
                result.message
            );

            setTimeout(() => {

                closeBillingForm();

            }, 800);

        }

        catch (error) {

            showError(
                error.message
            );

        }

        finally {

            btnSaveBillingProfile.disabled = false;

        }

    }

    /*
    |--------------------------------------------------------------------------
    | Validar formulario
    |--------------------------------------------------------------------------
    */

    function validateForm()
    {
        if (billingRuc.value.trim().length !== 11) {

            showError(
                'Ingrese un RUC válido.'
            );

            billingRuc.focus();

            return false;

        }

        if (
            billingRazonSocial.value.trim() === ''
        ) {

            showError(
                'Consulte primero el RUC.'
            );

            billingRuc.focus();

            return false;

        }

        if (
            billingAlias.value.trim() === ''
        ) {

            showError(
                'Ingrese un nombre para identificar la empresa.'
            );

            billingAlias.focus();

            return false;

        }

        return true;
    }

    /*
    |--------------------------------------------------------------------------
    | Agregar empresa al select
    |--------------------------------------------------------------------------
    */

    function appendBillingProfile(profile)
    {
        const option = document.createElement(
            'option'
        );

        option.value = profile.id;

        option.textContent =
            `${profile.alias} - ${profile.ruc}`;

        option.selected = true;

        billingProfile.appendChild(
            option
        );

        billingProfile.value =
            profile.id;
    }

    /*
    |--------------------------------------------------------------------------
    | Validación Checkout
    |--------------------------------------------------------------------------
    */

    checkoutForm.addEventListener(
        'submit',
        event => {

            const billingType =
                document.querySelector(
                    'input[name="billing_type"]:checked'
                )?.value;

            if (
                billingType === 'FACTURA' &&
                billingProfile.value === ''
            ) {

                event.preventDefault();

                showError(
                    'Seleccione una empresa para emitir la factura.'
                );

                billingProfile.focus();

            }

        }
    );

    /*
    |--------------------------------------------------------------------------
    | Inicializar
    |--------------------------------------------------------------------------
    */

    toggleBillingType();

});