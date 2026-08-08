// resources/js/modules/cart/events.js

import { MAX_QTY } from './config';

import {
    addProduct,
    updateProduct,
    removeProduct,
    clearCart
} from './api';

/*
|--------------------------------------------------------------------------
| Helpers
|--------------------------------------------------------------------------
*/

function showCart() {

    // Si estamos dentro del carrito no abrir Offcanvas
    if (document.body.classList.contains('cart-page')) {
        return;
    }

    const offcanvas = document.getElementById('cartOffcanvas');

    if (!offcanvas || !window.bootstrap) {
        return;
    }

    window.bootstrap
        .Offcanvas
        .getOrCreateInstance(offcanvas)
        .show();

}

/*
|--------------------------------------------------------------------------
| Agregar producto
|--------------------------------------------------------------------------
*/

export function bindAddToCart(onUpdate) {

    document.addEventListener('click', async (e) => {

        const button = e.target.closest('.btn-add-to-cart');

        if (!button) return;

        e.preventDefault();

        if (button.disabled) return;

        button.disabled = true;

        const originalHtml = button.innerHTML;

        button.innerHTML = `
            <span class="spinner-border spinner-border-sm me-2"></span>
            Agregando...
        `;

        try {

            const productId = Number(button.dataset.productId);

            const quantity = Math.max(
                1,
                Math.min(
                    MAX_QTY,
                    Number(button.dataset.quantity ?? 1)
                )
            );

            const cart = await addProduct(
                productId,
                quantity
            );

            await onUpdate(cart);

            showCart();

        } catch (error) {

            console.error('[ADD CART]', error);

        } finally {

            button.disabled = false;

            button.innerHTML = originalHtml;

        }

    });

}

/*
|--------------------------------------------------------------------------
| Actualizar cantidades / eliminar
|--------------------------------------------------------------------------
*/

export function bindCartActions(onUpdate) {

    const container = document.getElementById('cartItems');

    if (!container) return;

    container.addEventListener('click', async (e) => {

        const inc = e.target.closest('.btn-inc');
        const dec = e.target.closest('.btn-dec');
        const remove = e.target.closest('.btn-remove');

        try {

            /*
            |--------------------------------------------------------------------------
            | Incrementar / disminuir
            |--------------------------------------------------------------------------
            */

            if (inc || dec) {

                const button = inc || dec;

                if (button.disabled) return;

                button.disabled = true;

                const productId = Number(
                    button.dataset.productId
                );

                const quantityBox = button
                    .parentElement
                    .querySelector('.btn-light');

                let quantity = Number(
                    quantityBox.textContent
                );

                quantity = inc
                    ? Math.min(MAX_QTY, quantity + 1)
                    : Math.max(1, quantity - 1);

                const cart = await updateProduct(
                    productId,
                    quantity
                );

                await onUpdate(cart);

                return;

            }

            /*
            |--------------------------------------------------------------------------
            | Eliminar
            |--------------------------------------------------------------------------
            */

            if (remove) {

                if (remove.disabled) return;

                remove.disabled = true;

                const productId = Number(
                    remove.dataset.productId
                );

                const cart = await removeProduct(
                    productId
                );

                await onUpdate(cart);

            }

        } catch (error) {

            console.error('[UPDATE CART]', error);

        } finally {

            document
                .querySelectorAll(
                    '.btn-inc,.btn-dec,.btn-remove'
                )
                .forEach(btn => btn.disabled = false);

        }

    });

}

/*
|--------------------------------------------------------------------------
| Vaciar carrito
|--------------------------------------------------------------------------
*/

export function bindClearCart(onUpdate) {

    const button = document.getElementById(
        'btnClearCart'
    );

    if (!button) return;

    button.addEventListener('click', async (e) => {

        e.preventDefault();

        if (button.disabled) return;

        if (!confirm('¿Deseas vaciar el carrito?')) {
            return;
        }

        button.disabled = true;

        try {

            const cart = await clearCart();

            await onUpdate(cart);

        } catch (error) {

            console.error('[CLEAR CART]', error);

        } finally {

            button.disabled = false;

        }

    });

}