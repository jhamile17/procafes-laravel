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

    // Si estamos en la página del carrito,
    // no abrir el Offcanvas.
    if (document.body.classList.contains('cart-page')) {
        return;
    }

    const element = document.getElementById('cartOffcanvas');

    if (!element || !window.bootstrap) {
        return;
    }

    window.bootstrap
        .Offcanvas
        .getOrCreateInstance(element)
        .show();

}

/*
|--------------------------------------------------------------------------
| Agregar producto
|--------------------------------------------------------------------------
*/

export function bindAddToCart(onUpdate) {

    document.addEventListener('click', async (e) => {

        const btn = e.target.closest('.btn-add-to-cart');

        if (!btn) {
            return;
        }

        e.preventDefault();

        if (btn.disabled) {
            return;
        }

        const productId = Number(
            btn.dataset.productId
        );

        const quantity = Math.max(
            1,
            Math.min(
                MAX_QTY,
                Number(btn.dataset.quantity ?? 1)
            )
        );

        const originalHtml = btn.innerHTML;

        btn.disabled = true;

        btn.innerHTML = `
            <span class="spinner-border spinner-border-sm me-2"></span>
            Agregando...
        `;

        try {

            const cart = await addProduct(
                productId,
                quantity
            );

            onUpdate(cart);

            // Solo al agregar desde el catálogo
            showCart();

        } catch (error) {

            console.error('[CART]', error);

        } finally {

            btn.disabled = false;

            btn.innerHTML = originalHtml;

        }

    });

}

/*
|--------------------------------------------------------------------------
| Incrementar, disminuir y eliminar
|--------------------------------------------------------------------------
*/

export function bindCartActions(onUpdate) {

    const itemsBox = document.getElementById('cartItems');

    if (!itemsBox) {
        return;
    }

    itemsBox.addEventListener('click', async (e) => {

        const inc = e.target.closest('.btn-inc');
        const dec = e.target.closest('.btn-dec');
        const remove = e.target.closest('.btn-remove');

        try {

            /*
            |--------------------------------------------------------------
            | Incrementar / Disminuir
            |--------------------------------------------------------------
            */

            if (inc || dec) {

                const button = inc || dec;

                if (button.disabled) {
                    return;
                }

                button.disabled = true;

                const productId = Number(
                    button.dataset.productId
                );

                const quantityButton = button
                    .parentElement
                    .querySelector('.btn-light');

                let quantity = Number(
                    quantityButton.textContent
                );

                quantity = inc
                    ? Math.min(MAX_QTY, quantity + 1)
                    : Math.max(1, quantity - 1);

                const cart = await updateProduct(
                    productId,
                    quantity
                );

                onUpdate(cart);

                button.disabled = false;

                return;

            }

            /*
            |--------------------------------------------------------------
            | Eliminar producto
            |--------------------------------------------------------------
            */

            if (remove) {

                remove.disabled = true;

                const productId = Number(
                    remove.dataset.productId
                );

                const cart = await removeProduct(
                    productId
                );

                onUpdate(cart);

            }

        } catch (error) {

            console.error('[CART]', error);

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

    if (!button) {
        return;
    }

    button.addEventListener('click', async (e) => {

        e.preventDefault();

        if (!confirm('¿Deseas vaciar el carrito?')) {
            return;
        }

        button.disabled = true;

        try {

            const cart = await clearCart();

            onUpdate(cart);

        } catch (error) {

            console.error('[CART]', error);

        } finally {

            button.disabled = false;

        }

    });

}