// resources/js/cart/cart.js

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

    /**
     * Obtener el carrito desde el servidor
     */
    async refresh() {

        try {

            const cart = await getCart();

            await this.update(cart);

        } catch (error) {

            console.error('[CART]', error);

        }

    }

    /**
     * Actualizar la interfaz
     */
        async update(cart) {

        render(cart);

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

    /**
     * Inicializar el módulo
     */
    init() {

        this.refresh();

        const update = async (cart) => {
            await this.update(cart);
        };

        bindAddToCart(update);

        bindCartActions(update);

        bindClearCart(update);

    }

}

export default new Cart();