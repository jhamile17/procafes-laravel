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
        this.version = 0;
        this.isCartPage = document.body.classList.contains('cart-page');
    }

    reload() {
        return this.refresh();
    }

    async refresh() {
        const version = ++this.version;

        try {
            const cart = await getCart();

            // Ignore a response that started before a more recent cart action.
            if (version !== this.version) {
                return;
            }

            render(cart);
            void this.loadRecommendations(version);

        } catch (error) {
            console.error(error);
        }
    }

    async loadRecommendations(version = this.version) {
        if (!this.isCartPage) {
            return;
        }

        try {
            const html = await getRecommendations();

            if (version === this.version) {
                renderRecommendations(html);
            }

        } catch (error) {
            console.error(error);
        }
    }

    update(cart) {
        const version = ++this.version;

        render(cart);
        void this.loadRecommendations(version);
    }

    init() {
        bindAddToCart(this.update.bind(this));
        bindCartActions(this.update.bind(this));
        bindClearCart(this.update.bind(this));

        void this.refresh();
    }

}

export default new Cart();
