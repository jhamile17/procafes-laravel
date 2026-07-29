// resources/js/cart/cart.js

import { getCart } from './api';

import { render } from './render';

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

            this.update(cart);

        } catch (error) {

            console.error('[CART]', error);

        }

    }

    /**
     * Actualizar la interfaz
     */
    update(cart) {

        render(cart);

    }

    /**
     * Inicializar el módulo
     */
    init() {

        this.refresh();

        const update = this.update.bind(this);

        bindAddToCart(update);

        bindCartActions(update);

        bindClearCart(update);

    }

}

export default new Cart();