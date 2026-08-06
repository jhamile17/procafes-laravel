const Payment = {
    init() {
        this.cache();
        if (!this.storeRadio) {
            return;
        }
        this.bindEvents();
        this.updatePanels();
    },

    cache() {

        this.storeRadio = document.getElementById('paymentStore');
        this.mercadoPagoRadio = document.getElementById('paymentMercadoPago');
        this.storePanel = document.getElementById('paymentStorePanel');
        this.mercadoPagoPanel = document.getElementById('paymentMercadoPagoPanel');
    },
    bindEvents() {
        this.storeRadio.addEventListener(
            'change',
            () => this.updatePanels()
        );
        this.mercadoPagoRadio.addEventListener(
            'change',
            () => this.updatePanels()
        );
    },

    /*Actualizar paneles*/
    updatePanels() {
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