/**
 * ==========================================================================
 * PAYMENT
 * ==========================================================================
 */

const Payment = {

    /*
    |--------------------------------------------------------------------------
    | Inicializar
    |--------------------------------------------------------------------------
    */

    init() {

        this.cache();

        if (!this.storeRadio) {

            return;

        }

        this.bindEvents();

        this.update();

    },

    /*
    |--------------------------------------------------------------------------
    | Cache
    |--------------------------------------------------------------------------
    */

    cache() {

        this.storeRadio = document.querySelector(
            '#paymentStore'
        );

        this.mercadoPagoRadio = document.querySelector(
            '#paymentMercadoPago'
        );

        this.storePanel = document.querySelector(
            '#paymentStorePanel'
        );

        this.mercadoPagoPanel = document.querySelector(
            '#paymentMercadoPagoPanel'
        );

    },

    /*
    |--------------------------------------------------------------------------
    | Eventos
    |--------------------------------------------------------------------------
    */

    bindEvents() {

        this.storeRadio?.addEventListener(

            'change',

            () => this.update()

        );

        this.mercadoPagoRadio?.addEventListener(

            'change',

            () => this.update()

        );

    },

    /*
    |--------------------------------------------------------------------------
    | Actualizar paneles
    |--------------------------------------------------------------------------
    */

    update() {

        const store = this.storeRadio?.checked;

        this.storePanel?.classList.toggle(

            'd-none',

            !store

        );

        this.mercadoPagoPanel?.classList.toggle(

            'd-none',

            store

        );

    }

};

document.addEventListener(

    'DOMContentLoaded',

    () => Payment.init()

);