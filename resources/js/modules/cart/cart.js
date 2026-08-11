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
    reload(){
        return this.refresh();
    }

    /*
    |--------------------------------------------------------------------------
    | Obtener carrito desde Laravel
    |--------------------------------------------------------------------------
    */

    async refresh() {

        if (this.loading) {
            return;
        }

        this.loading = true;

        try {

            const cart = await getCart();

            render(cart);

            await this.loadRecommendations();

        }

        catch (error) {

            console.error(error);

        }

        finally {

            this.loading = false;

        }

    }

    /*
    |--------------------------------------------------------------------------
    | Recomendaciones
    |--------------------------------------------------------------------------
    */

    async loadRecommendations() {

        try {

            const html = await getRecommendations();

            renderRecommendations(html);

        }

        catch (e) {

            console.error(e);

        }

    }

    /*
    |--------------------------------------------------------------------------
    | Cuando alguna acción devuelve un carrito actualizado
    |--------------------------------------------------------------------------
    */

    async update(cart) {

        render(cart);

        await this.loadRecommendations();

    }

    /*
    |--------------------------------------------------------------------------
    | Inicializar
    |--------------------------------------------------------------------------
    */

    init() {

        bindAddToCart(
            this.update.bind(this)
        );

        bindCartActions(
            this.update.bind(this)
        );

        bindClearCart(
            this.update.bind(this)
        );

        this.refresh();

    }

}

export default new Cart();