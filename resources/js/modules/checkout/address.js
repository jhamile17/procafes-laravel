/*
|--------------------------------------------------------------------------
| Checkout - Dirección de destino
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Elementos
|--------------------------------------------------------------------------
*/

const addressView = document.getElementById('addressView');
const addressForm = document.getElementById('addressForm');

const addressDireccion = document.getElementById('addressDireccion');
const addressUbicacion = document.getElementById('addressUbicacion');
const addressDepartamento = document.getElementById('addressDepartamento');

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

let timer = null;

/*
|--------------------------------------------------------------------------
| Editar dirección
|--------------------------------------------------------------------------
*/

if (btnEditAddress) {

    btnEditAddress.addEventListener('click', () => {

        addressView?.classList.add('d-none');

        addressForm?.classList.remove('d-none');

        input.value = '';

        results.innerHTML = '';

        input.focus();

    });

}

/*
|--------------------------------------------------------------------------
| Cancelar edición
|--------------------------------------------------------------------------
*/

if (btnCancelAddress) {

    btnCancelAddress.addEventListener('click', () => {

        addressForm?.classList.add('d-none');

        addressView?.classList.remove('d-none');

        input.value = '';

        results.innerHTML = '';

    });

}

/*
|--------------------------------------------------------------------------
| Buscar mientras escribe
|--------------------------------------------------------------------------
*/

if (input && results) {

    input.addEventListener('input', () => {

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

}

/*
|--------------------------------------------------------------------------
| Buscar en LocationIQ
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

        console.error(
            '[LocationIQ]',
            error
        );

    }

}

/*
|--------------------------------------------------------------------------
| Mostrar resultados
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

    /*
    |--------------------------------------------------------------------------
    | Hidden
    |--------------------------------------------------------------------------
    */

    direccion.value = address.direccion ?? '';

    departamento.value = address.departamento ?? '';

    provincia.value = address.provincia ?? '';

    distrito.value = address.distrito ?? '';

    latitude.value = address.latitude ?? '';

    longitude.value = address.longitude ?? '';

    /*
    |--------------------------------------------------------------------------
    | Actualizar tarjeta
    |--------------------------------------------------------------------------
    */

    if (addressDireccion) {

        addressDireccion.textContent =
            address.direccion;

    }

    if (addressUbicacion) {

        addressUbicacion.textContent =
            `${address.distrito}, ${address.provincia}`;

    }

    if (addressDepartamento) {

        addressDepartamento.textContent =
            address.departamento;

    }

    /*
    |--------------------------------------------------------------------------
    | Limpiar buscador
    |--------------------------------------------------------------------------
    */

    input.value = '';

    results.innerHTML = '';

    /*
    |--------------------------------------------------------------------------
    | Mostrar tarjeta
    |--------------------------------------------------------------------------
    */

    addressForm?.classList.add('d-none');

    addressView?.classList.remove('d-none');

}