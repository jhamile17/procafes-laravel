/**
 * ==========================================================================
 * DELIVERY
 * ==========================================================================
 */

const Delivery = {

    /*
    |--------------------------------------------------------------------------
    | Inicializar
    |--------------------------------------------------------------------------
    */

    init() {

        this.timer = null;

        this.cache();

        if (!this.pickupRadio) {
            return;
        }

        this.bindEvents();

        this.updatePanels();

    },

    /*
    |--------------------------------------------------------------------------
    | Cache DOM
    |--------------------------------------------------------------------------
    */

    cache() {

        /*
        |--------------------------------------------------------------------------
        | Tipo de entrega
        |--------------------------------------------------------------------------
        */
        this.routes = window.Laravel.routes;
        this.csrfToken =
        document.querySelector('meta[name="csrf-token"]').content;
    
        this.pickupRadio = document.querySelector('#deliveryPickup');

        this.shippingRadio = document.querySelector('#deliveryShipping');

        this.pickupPanel = document.querySelector('#pickupPanel');

        this.deliveryPanel = document.querySelector('#deliveryPanel');

        /*
        |--------------------------------------------------------------------------
        | Dirección
        |--------------------------------------------------------------------------
        */

        this.addressView = document.querySelector('#addressView');

        this.addressForm = document.querySelector('#addressForm');

        /*
        |--------------------------------------------------------------------------
        | Botones
        |--------------------------------------------------------------------------
        */

        this.btnEdit = document.querySelector('#btnEditAddress');

        this.btnAdd = document.querySelector('#btnAddAddress');

        this.btnCancel = document.querySelector('#btnCancelAddress');

        this.btnSave = document.querySelector('#btnSaveAddress');

        /*
        |--------------------------------------------------------------------------
        | Buscador
        |--------------------------------------------------------------------------
        */

        this.searchInput = document.querySelector('#addressSearch');

        this.results = document.querySelector('#addressResults');

        this.loading = document.querySelector('#addressLoading');

        this.message = document.querySelector('#addressMessage');

        /*
        |--------------------------------------------------------------------------
        | Campos
        |--------------------------------------------------------------------------
        */

        this.addressId = document.querySelector('#addressId');

        this.direccion = document.querySelector('#direccion');

        this.departamento = document.querySelector('#departamento');

        this.provincia = document.querySelector('#provincia');

        this.distrito = document.querySelector('#distrito');

        this.referencia = document.querySelector('#referencia');

        this.latitude = document.querySelector('#latitude');

        this.longitude = document.querySelector('#longitude');

    },
        /*
    |--------------------------------------------------------------------------
    | Eventos
    |--------------------------------------------------------------------------
    */

    bindEvents() {

        /*
        |--------------------------------------------------------------------------
        | Tipo de entrega
        |--------------------------------------------------------------------------
        */

        this.pickupRadio?.addEventListener(
            'change',
            () => this.updatePanels()
        );

        this.shippingRadio?.addEventListener(
            'change',
            () => this.updatePanels()
        );

        /*
        |--------------------------------------------------------------------------
        | Dirección
        |--------------------------------------------------------------------------
        */

        this.btnEdit?.addEventListener(
            'click',
            () => this.showForm()
        );

        this.btnAdd?.addEventListener(
            'click',
            () => this.showForm()
        );

        this.btnCancel?.addEventListener(
            'click',
            () => this.hideForm()
        );

        /*
        |--------------------------------------------------------------------------
        | Buscador
        |--------------------------------------------------------------------------
        */

        this.searchInput?.addEventListener(
            'input',
            () => this.debounceSearch()
        );

        /*
        |--------------------------------------------------------------------------
        | Guardar
        |--------------------------------------------------------------------------
        */

        this.btnSave?.addEventListener(
            'click',
            () => this.saveAddress()
        );

    },

    /*
    |--------------------------------------------------------------------------
    | Mostrar paneles
    |--------------------------------------------------------------------------
    */

    updatePanels() {

        const pickupSelected = this.pickupRadio?.checked;

        this.pickupPanel?.classList.toggle(
            'd-none',
            !pickupSelected
        );

        this.deliveryPanel?.classList.toggle(
            'd-none',
            pickupSelected
        );

    },
        /*
    |--------------------------------------------------------------------------
    | Mostrar formulario
    |--------------------------------------------------------------------------
    */

    showForm() {

        this.addressView?.classList.add('d-none');

        this.btnAdd?.classList.add('d-none');

        this.addressForm?.classList.remove('d-none');

        this.searchInput?.focus();

    },

    /*
    |--------------------------------------------------------------------------
    | Ocultar formulario
    |--------------------------------------------------------------------------
    */

    hideForm() {

        this.addressForm?.classList.add('d-none');

        this.addressView?.classList.remove('d-none');

        this.btnAdd?.classList.remove('d-none');

        this.results && (this.results.innerHTML = '');

        this.message && (this.message.innerHTML = '');

        if(this.searchInput){

            this.searchInput.value = '';

        }

    },
        /*
    |--------------------------------------------------------------------------
    | Buscar dirección (Debounce)
    |--------------------------------------------------------------------------
    */

    debounceSearch() {

        clearTimeout(this.timer);

        const query = this.searchInput.value.trim();

        if (query.length < 2) {

            this.results.innerHTML = '';

            this.loading?.classList.add('d-none');

            return;

        }

        this.timer = setTimeout(() => {

            this.searchAddress(query);

        }, 400);

    },

    /*
    |--------------------------------------------------------------------------
    | Buscar dirección
    |--------------------------------------------------------------------------
    */

    async searchAddress(query) {

        this.loading?.classList.remove('d-none');

        this.results.innerHTML = '';

        try {

            const response = await fetch(

                `${this.routes.address.search}?q=${encodeURIComponent(query)}`,
                {
                    headers: {
                        'Accept': 'application/json'
                    }
                }

            );

            if (!response.ok) {

                throw new Error('No fue posible consultar las direcciones.');

            }

            const json = await response.json();

            this.renderResults(json.data ?? []);

        }

        catch (error) {

            console.error(error);

            this.showMessage(

                'Ocurrió un error al buscar direcciones.',

                'error'

            );

        }

        finally {

            this.loading?.classList.add('d-none');

        }

    },
        /*
    |--------------------------------------------------------------------------
    | Mostrar resultados
    |--------------------------------------------------------------------------
    */

    renderResults(results) {

        this.results.innerHTML = '';

        if (!results.length) {

            this.results.innerHTML = `

                <div class="checkout-empty-results">

                    <i class="bi bi-geo-alt"></i>

                    <span>

                        No se encontraron direcciones.

                    </span>

                </div>

            `;

            return;

        }

        results.forEach(address => {

            const item = document.createElement('button');

            item.type = 'button';

            item.className = 'checkout-search-item';

            item.innerHTML = `

                <div class="checkout-search-icon">

                    <i class="bi bi-geo-alt-fill"></i>

                </div>

                <div class="checkout-search-content">

                    <strong>

                        ${address.direccion}

                    </strong>

                    <small>

                        ${address.label}

                    </small>

                </div>

            `;

            item.addEventListener(

                'click',

                () => this.selectAddress(address)

            );

            this.results.appendChild(item);

        });

    },

    /*
    |--------------------------------------------------------------------------
    | Seleccionar dirección
    |--------------------------------------------------------------------------
    */

    selectAddress(address) {

        this.searchInput.value = address.label;

        this.direccion.value = address.direccion;

        this.departamento.value = address.departamento;

        this.provincia.value = address.provincia;

        this.distrito.value = address.distrito;

        this.latitude.value = address.latitude;

        this.longitude.value = address.longitude;

        this.results.innerHTML = '';

        this.showMessage(

            'Dirección seleccionada correctamente.',

            'success'

        );

    },
        /*
    |--------------------------------------------------------------------------
    | Guardar dirección
    |--------------------------------------------------------------------------
    */

    async saveAddress() {

        if (!this.direccion.value) {

            this.showMessage(

                'Selecciona una dirección antes de guardar.',

                'error'

            );

            return;

        }

        this.setLoading(true);

        try {

            const response = await fetch(

                this.routes.address.update,

                {

                    method: 'POST',

                    headers: {

                        'Content-Type': 'application/json',

                        'Accept': 'application/json',

                        'X-CSRF-TOKEN': this.csrfToken,

                    },

                    body: JSON.stringify({

                        direccion: this.direccion.value,

                        referencia: this.referencia.value,

                        departamento: this.departamento.value,

                        provincia: this.provincia.value,

                        distrito: this.distrito.value,

                        latitude: this.latitude.value,

                        longitude: this.longitude.value,

                    })

                }

            );

            const json = await response.json();

            if (!response.ok || !json.success) {

                throw new Error(

                    json.message ??

                    'No fue posible guardar la dirección.'

                );

            }

            this.updateAddressCard(

                json.data

            );

            this.hideForm();

            this.showMessage(

                json.message,

                'success'

            );

        }

        catch (error) {

            console.error(error);

            this.showMessage(

                error.message,

                'error'

            );

        }

        finally {

            this.setLoading(false);

        }

    },
        /*
    |--------------------------------------------------------------------------
    | Estado de carga
    |--------------------------------------------------------------------------
    */

    setLoading(status) {

        if (this.btnSave) {

            this.btnSave.disabled = status;

            this.btnSave.innerHTML = status

                ? `
                    <i class="bi bi-arrow-repeat spinner-border spinner-border-sm"></i>
                    <span>Guardando...</span>
                `

                : `
                    <i class="bi bi-check-circle"></i>
                    <span>Guardar dirección</span>
                `;

        }

        if (this.searchInput) {

            this.searchInput.disabled = status;

        }

    },

    /*
    |--------------------------------------------------------------------------
    | Actualizar tarjeta
    |--------------------------------------------------------------------------
    */

    updateAddressCard(address) {

        if (!this.addressView) {
            return;
        }

        const title = this.addressView.querySelector('h4');

        const city = this.addressView.querySelector('p');

        const department = this.addressView.querySelector('small');

        if (title) {

            title.textContent = address.direccion;

        }

        if (city) {

            city.textContent = `${address.distrito}, ${address.provincia}`;

        }

        if (department) {

            department.textContent = address.departamento;

        }

    },

    /*
    |--------------------------------------------------------------------------
    | Mensajes
    |--------------------------------------------------------------------------
    */

    showMessage(message, type = 'success') {

        if (!this.message) {
            return;
        }

        this.message.className = `checkout-message checkout-message-${type}`;

        this.message.innerHTML = `

            <i class="bi ${type === 'success'
                ? 'bi-check-circle-fill'
                : 'bi-exclamation-circle-fill'}"></i>

            <span>${message}</span>

        `;

        setTimeout(() => {

            this.message.innerHTML = '';

            this.message.className = 'checkout-message';

        }, 4000);

    }
};

/*
|--------------------------------------------------------------------------
| Inicializar
|--------------------------------------------------------------------------
*/

document.addEventListener('DOMContentLoaded', () => {

    Delivery.init();

});