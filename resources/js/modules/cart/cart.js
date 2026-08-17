// resources/js/modules/cart/cart.js

import {
    getCart,
    getRecommendations,
} from './api';

import {
    render,
    renderRecommendations,
    showLoading,
} from './render';

import {
    initializeCartEvents,
} from './events';


class Cart {

    constructor() {

        this.initialized =
            false;

    }


    /*=====================================================
        CARGAR CARRITO
    =====================================================*/

    async load() {

        try {

            const cart =
                await getCart();


            render(cart);


            /*
            |--------------------------------------------------------------------------
            | Recomendaciones solamente en página carrito
            |--------------------------------------------------------------------------
            */

            if (
                document.body.classList.contains(
                    'cart-page'
                )
            ) {

                try {

                    const html =
                        await getRecommendations();


                    renderRecommendations(
                        html
                    );

                } catch (error) {

                    console.error(
                        'Error cargando recomendaciones:',
                        error
                    );

                }

            }

        } catch (error) {

            console.error(
                'Error cargando carrito:',
                error
            );

        }

    }


    /*=====================================================
        INICIALIZAR
    =====================================================*/

    init() {

        if (this.initialized) {
            return;
        }


        this.initialized =
            true;


        initializeCartEvents();


        void this.load();

    }

}


const cart =
    new Cart();


cart.init();


export default cart;