/*
|--------------------------------------------------------------------------
| Checkout - Dirección de envío
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Elementos
|--------------------------------------------------------------------------
*/

const addressEmpty = document.getElementById('addressEmpty');
const addressView = document.getElementById('addressView');
const addressForm = document.getElementById('addressForm');

const addressDireccion = document.getElementById('addressDireccion');
const addressUbicacion = document.getElementById('addressUbicacion');
const addressDepartamento = document.getElementById('addressDepartamento');

const btnNuevaDireccion = document.getElementById('btnNuevaDireccion');
const btnEditAddress = document.getElementById('btnEditAddress');
const btnCancelAddress = document.getElementById('btnCancelAddress');

const input = document.getElementById('addressSearch');
const results = document.getElementById('addressResults');

const direccion = document.getElementById('direccion');
const departamento = document.getElementById('departamento');
const provincia = document.getElementById('provincia');
const distrito = document.getElementById('distrito');
const latitude = document.getElementById('latitude');
const longitude = document.getElementById('longitude');

const checkoutForm = document.getElementById('checkoutForm');
const submitButton = checkoutForm?.querySelector(
    'button[type="submit"]'
);

const paymentRadios = document.querySelectorAll(
    'input[name="payment_method_id"]'
);

let timer = null;

/*
|--------------------------------------------------------------------------
| Nueva dirección
|--------------------------------------------------------------------------
*/

btnNuevaDireccion?.addEventListener('click', () => {

    addressEmpty?.classList.add('d-none');

    addressForm?.classList.remove('d-none');

    input.focus();

});

/*
|--------------------------------------------------------------------------
| Editar dirección
|--------------------------------------------------------------------------
*/

btnEditAddress?.addEventListener('click', () => {

    addressView?.classList.add('d-none');

    addressForm?.classList.remove('d-none');

    input.value = '';

    results.innerHTML = '';

    input.focus();

});

/*
|--------------------------------------------------------------------------
| Cancelar edición
|--------------------------------------------------------------------------
*/

btnCancelAddress?.addEventListener('click', () => {

    addressForm?.classList.add('d-none');

    input.value = '';

    results.innerHTML = '';

    if (direccion.value.trim()) {

        addressView?.classList.remove('d-none');

    } else {

        addressEmpty?.classList.remove('d-none');

    }

});

/*
|--------------------------------------------------------------------------
| Buscar dirección
|--------------------------------------------------------------------------
*/

input?.addEventListener('input', () => {

    clearTimeout(timer);

    const query = input.value.trim();

    if (query.length < 2) {

        results.innerHTML = '';

        return;

    }

    timer = setTimeout(() => {

        buscar(query);

    }, 300);

});

/*
|--------------------------------------------------------------------------
| Buscar LocationIQ
|--------------------------------------------------------------------------
*/

async function buscar(query) {

    try {

        const response = await fetch(

            `${window.Laravel.routes.address.search}?q=${encodeURIComponent(query)}`

        );

        if (!response.ok) {

            throw new Error(
                'No fue posible consultar LocationIQ.'
            );

        }

        const addresses = await response.json();

        pintarResultados(addresses);

    } catch (error) {

        console.error('[LocationIQ]', error);

    }

}

/*
|--------------------------------------------------------------------------
| Pintar resultados
|--------------------------------------------------------------------------
*/

function pintarResultados(addresses) {

    results.innerHTML = '';

    if (!addresses.length) {

        results.innerHTML = `
            <div class="checkout-address-empty">
                No se encontraron direcciones.
            </div>
        `;

        return;

    }

    addresses.forEach(address => {

        const item = document.createElement('button');

        item.type = 'button';

        item.className = 'checkout-address-item';

        item.innerHTML = `
            <i class="bi bi-geo-alt-fill me-2"></i>
            ${address.label}
        `;

        item.addEventListener('click', () => {

            seleccionarDireccion(address);

        });

        results.appendChild(item);

    });

}

/*
|--------------------------------------------------------------------------
| Seleccionar dirección
|--------------------------------------------------------------------------
*/

function seleccionarDireccion(address) {

    direccion.value = address.direccion ?? '';

    departamento.value = address.departamento ?? '';

    provincia.value = address.provincia ?? '';

    distrito.value = address.distrito ?? '';

    latitude.value = address.latitude ?? '';

    longitude.value = address.longitude ?? '';

    addressDireccion.textContent =
        address.direccion;

    addressUbicacion.textContent =
        `${address.distrito}, ${address.provincia}`;

    addressDepartamento.textContent =
        address.departamento;

    input.value = '';

    results.innerHTML = '';

    addressEmpty?.classList.add('d-none');

    addressForm?.classList.add('d-none');

    addressView?.classList.remove('d-none');

    addressView.animate(
        [
            {
                opacity: 0,
                transform: 'translateY(12px)'
            },
            {
                opacity: 1,
                transform: 'translateY(0)'
            }
        ],
        {
            duration: 250,
            easing: 'ease-out'
        }
    );

}

/*
|--------------------------------------------------------------------------
| Cambiar texto del botón
|--------------------------------------------------------------------------
*/

function actualizarBotonPago() {

    if (!submitButton) return;

    const seleccionado = document.querySelector(
        'input[name="payment_method_id"]:checked'
    );

    if (!seleccionado) {

        submitButton.disabled = true;

        submitButton.innerHTML = `
            <i class="bi bi-exclamation-circle me-2"></i>
            Selecciona un método de pago
        `;

        return;

    }

    submitButton.disabled = false;

    const metodo = seleccionado.dataset.payment;

    if (metodo === 'mercado-pago') {

        submitButton.innerHTML = `
            <i class="bi bi-credit-card-fill me-2"></i>
            Pagar con Mercado Pago
        `;

    } else {

        submitButton.innerHTML = `
            <i class="bi bi-bag-check-fill me-2"></i>
            Confirmar pedido
        `;

    }

}

paymentRadios.forEach(radio => {

    radio.addEventListener(
        'change',
        actualizarBotonPago
    );

});

actualizarBotonPago();

/*
|--------------------------------------------------------------------------
| Validar Checkout
|--------------------------------------------------------------------------
*/

checkoutForm?.addEventListener('submit', function (e) {

    if (!direccion.value.trim()) {

        e.preventDefault();

        Swal.fire({

            icon: 'warning',

            title: 'Dirección requerida',

            text: 'Debes seleccionar una dirección antes de continuar con la compra.'

        });

        return;

    }

    const metodo = document.querySelector(
        'input[name="payment_method_id"]:checked'
    );

    if (!metodo) {

        e.preventDefault();

        Swal.fire({

            icon: 'warning',

            title: 'Método de pago',

            text: 'Selecciona un método de pago.'

        });

        return;

    }

    submitButton.disabled = true;

    submitButton.innerHTML = `
        <span
            class="spinner-border spinner-border-sm me-2"
            role="status">
        </span>

        Procesando...
    `;

});