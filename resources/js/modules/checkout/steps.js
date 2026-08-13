const CheckoutSteps = {

    currentStep: 1,

    steps: {
        1: document.getElementById('checkoutStep1'),
        2: document.getElementById('checkoutStep2'),
        3: document.getElementById('checkoutStep3'),
    },

    indicators: {
        1: document.getElementById('stepIndicator1'),
        2: document.getElementById('stepIndicator2'),
        3: document.getElementById('stepIndicator3'),
    },

    init() {

        this.cache();

        if (!this.steps[1]) {
            return;
        }

        this.bindEvents();

        this.showStep(1);
    },

    cache() {

        this.steps = {
            1: document.getElementById('checkoutStep1'),
            2: document.getElementById('checkoutStep2'),
            3: document.getElementById('checkoutStep3'),
        };

        this.indicators = {
            1: document.getElementById('stepIndicator1'),
            2: document.getElementById('stepIndicator2'),
            3: document.getElementById('stepIndicator3'),
        };

        this.btnNext1 = document.getElementById('checkoutNext1');
        this.btnNext2 = document.getElementById('checkoutNext2');

        this.btnBack2 = document.getElementById('checkoutBack2');
        this.btnBack3 = document.getElementById('checkoutBack3');
    },

    bindEvents() {

        this.btnNext1?.addEventListener(
            'click',
            () => {

                if (!this.validateStep1()) {
                    return;
                }

                this.showStep(2);
            }
        );

        this.btnNext2?.addEventListener(
            'click',
            () => {

                if (!this.validateStep2()) {
                    return;
                }

                this.showStep(3);
            }
        );

        this.btnBack2?.addEventListener(
            'click',
            () => this.showStep(1)
        );

        this.btnBack3?.addEventListener(
            'click',
            () => this.showStep(2)
        );
    },

    showStep(step) {

        this.currentStep = step;

        Object.entries(this.steps).forEach(
            ([number, element]) => {

                if (!element) {
                    return;
                }

                element.classList.toggle(
                    'd-none',
                    Number(number) !== step
                );
            }
        );

        Object.entries(this.indicators).forEach(
            ([number, indicator]) => {

                if (!indicator) {
                    return;
                }

                const numberValue = Number(number);

                indicator.classList.toggle(
                    'is-active',
                    numberValue === step
                );

                indicator.classList.toggle(
                    'is-completed',
                    numberValue < step
                );
            }
        );

        window.scrollTo({
            top: 0,
            behavior: 'smooth',
        });
    },

    validateStep1() {

        const pickup = document.getElementById('deliveryPickup');
        const shipping = document.getElementById('deliveryShipping');

        if (!pickup && !shipping) {
            return true;
        }

        if (!pickup?.checked && !shipping?.checked) {

            this.showError(
                'Selecciona un método de entrega antes de continuar.'
            );

            return false;
        }

        /*
        |--------------------------------------------------------------------------
        | Si es delivery, debe existir una dirección
        |--------------------------------------------------------------------------
        */

        if (shipping?.checked) {

            const direccion =
                document.getElementById('direccion');

            if (
                !direccion ||
                !direccion.value.trim()
            ) {

                this.showError(
                    'Selecciona o registra una dirección de entrega antes de continuar.'
                );

                return false;
            }
        }

        return true;
    },

    validateStep2() {

        const paymentStore =
            document.getElementById('paymentStore');

        const paymentMercadoPago =
            document.getElementById('paymentMercadoPago');

        if (
            paymentStore &&
            paymentMercadoPago &&
            !paymentStore.checked &&
            !paymentMercadoPago.checked
        ) {

            this.showError(
                'Selecciona un método de pago antes de continuar.'
            );

            return false;
        }

        return true;
    },

    showError(message) {

        /*
        |--------------------------------------------------------------------------
        | Utilizar el toast existente del checkout
        |--------------------------------------------------------------------------
        */

        const toast =
            document.getElementById('toast');

        const toastMessage =
            document.getElementById('toastMessage');

        if (
            toast &&
            toastMessage
        ) {

            toastMessage.textContent = message;

            toast.classList.remove('show');

            toast.style.background = '#dc2626';

            setTimeout(() => {

                toast.classList.add('show');

            }, 50);

            clearTimeout(this.toastTimer);

            this.toastTimer = setTimeout(() => {

                toast.classList.remove('show');

            }, 3000);

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Fallback
        |--------------------------------------------------------------------------
        */

        alert(message);
    },

};

document.addEventListener(
    'DOMContentLoaded',
    () => CheckoutSteps.init()
);