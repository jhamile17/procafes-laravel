// resources/js/modules/cart/cart.js

import {
    getCart,
    getRecommendations
} from './api';

import {
    render,
    renderRecommendations
} from './render';

import {
    bindAddToCart,
    bindCartActions,
    bindClearCart
} from './events';
class Cart {

    constructor() {

        this.loading = false;

    }

    /*
    |--------------------------------------------------------------------------
    | Obtener carrito
    |--------------------------------------------------------------------------
    */

    async refresh() {

        if (this.loading) {
            return;
        }

        this.loading = true;

        try {

            const cart = await getCart();

            await this.update(cart);

        } catch (error) {

            console.error('[CART]', error);

        } finally {

            this.loading = false;

        }

    }

    /*
    |--------------------------------------------------------------------------
    | Actualizar interfaz
    |--------------------------------------------------------------------------
    */

    async update(cart) {

        /*
        |--------------------------------------------------------------
        | Render del carrito
        |--------------------------------------------------------------
        */

        render(cart);

        /*
        |--------------------------------------------------------------
        | Recomendaciones
        |--------------------------------------------------------------
        */

        try {

            const html = await getRecommendations();

            renderRecommendations(html);

        } catch (error) {

            console.error(
                '[RECOMMENDATIONS]',
                error
            );

        }

    }

    /*
    |--------------------------------------------------------------------------
    | Inicializar
    |--------------------------------------------------------------------------
    */

    init() {

        const update = async (cart) => {

            await this.update(cart);

        };

        bindAddToCart(update);

        bindCartActions(update);

        bindClearCart(update);

        this.refresh();

    }

}

export default new Cart();