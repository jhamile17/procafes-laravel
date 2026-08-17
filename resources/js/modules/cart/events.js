// resources/js/modules/cart/events.js

import {
    addProduct,
    updateProduct,
    removeProduct,
    clearCart,
} from './api';

import {
    render,
    showLoading,
    hideLoading,
} from './render';


/*=========================================================
    ABRIR OFFCANVAS
=========================================================*/

function showCart() {

    if (
        document.body.classList.contains(
            'cart-page'
        )
    ) {
        return;
    }


    const offcanvas =
        document.getElementById(
            'cartOffcanvas'
        );


    if (
        !offcanvas ||
        typeof bootstrap === 'undefined'
    ) {
        return;
    }


    bootstrap.Offcanvas
        .getOrCreateInstance(
            offcanvas
        )
        .show();

}


/*=========================================================
    ALERTA DEL PRODUCTO
=========================================================*/

function showButtonAlert(
    button,
    message
) {

    const parent =
        button.parentNode;


    const old =
        parent.querySelector(
            '.product-alert'
        );


    if (old) {

        old.remove();

    }


    const alert =
        document.createElement('div');


    alert.className =
        'product-alert';


    alert.innerHTML = `

        <i class="bi bi-exclamation-triangle-fill"></i>

        <span>
            ${message}
        </span>

    `;


    button.insertAdjacentElement(
        'afterend',
        alert
    );


    setTimeout(() => {

        alert.classList.add(
            'hide'
        );


        setTimeout(() => {

            alert.remove();

        }, 250);

    }, 2500);

}


/*=========================================================
    AGREGAR PRODUCTO
=========================================================*/

async function handleAdd(
    button
) {

    if (button.dataset.processing === 'true') {
        return;
    }


    button.dataset.processing =
        'true';


    button.disabled =
        true;


    const originalHtml =
        button.innerHTML;


    button.innerHTML = `

        <span
            class="spinner-border spinner-border-sm me-2">
        </span>

        Agregando...

    `;


    try {

        const response =
            await addProduct(
                Number(
                    button.dataset.productId
                ),
                Number(
                    button.dataset.quantity ?? 1
                )
            );


        /*
        |--------------------------------------------------------------------------
        | Laravel ya devuelve el carrito completo
        |--------------------------------------------------------------------------
        */

        render(response);


        showCart();


    } catch (error) {

        console.error(
            'Error agregando producto:',
            error
        );


        showButtonAlert(
            button,
            error.message ||
            'No se pudo agregar el producto.'
        );

    } finally {

        button.disabled =
            false;


        button.dataset.processing =
            'false';


        button.innerHTML =
            originalHtml;

    }

}


/*=========================================================
    INCREMENTAR
=========================================================*/

async function handleIncrement(
    button
) {

    if (button.disabled) {
        return;
    }


    button.disabled =
        true;


    try {

        const id =
            Number(
                button.dataset.productId
            );


        const quantity =
            Number(
                button.dataset.quantity
            ) + 1;


        const response =
            await updateProduct(
                id,
                quantity
            );


        render(response);


    } catch (error) {

        console.error(
            'Error aumentando cantidad:',
            error
        );


        showButtonAlert(
            button,
            error.message ||
            'No se pudo aumentar la cantidad.'
        );

    } finally {

        button.disabled =
            false;

    }

}


/*=========================================================
    DECREMENTAR
=========================================================*/

async function handleDecrement(
    button
) {

    if (button.disabled) {
        return;
    }


    const id =
        Number(
            button.dataset.productId
        );


    const quantity =
        Number(
            button.dataset.quantity
        ) - 1;


    if (quantity < 1) {
        return;
    }


    button.disabled =
        true;


    try {

        const response =
            await updateProduct(
                id,
                quantity
            );


        render(response);


    } catch (error) {

        console.error(
            'Error disminuyendo cantidad:',
            error
        );


        showButtonAlert(
            button,
            error.message ||
            'No se pudo actualizar la cantidad.'
        );

    } finally {

        button.disabled =
            false;

    }

}


/*=========================================================
    ELIMINAR
=========================================================*/

async function handleRemove(
    button
) {

    if (button.disabled) {
        return;
    }


    button.disabled =
        true;


    try {

        const response =
            await removeProduct(
                Number(
                    button.dataset.productId
                )
            );


        render(response);


    } catch (error) {

        console.error(
            'Error eliminando producto:',
            error
        );


        button.disabled =
            false;

    }

}


/*=========================================================
    VACIAR CARRITO
=========================================================*/

async function handleClear(
    button
) {

    if (button.disabled) {
        return;
    }


    button.disabled =
        true;


    try {

        const response =
            await clearCart();


        render(response);


    } catch (error) {

        console.error(
            'Error vaciando carrito:',
            error
        );

    } finally {

        button.disabled =
            false;

    }

}


/*=========================================================
    LISTENER PRINCIPAL
=========================================================*/

export function initializeCartEvents() {

    document.addEventListener(
        'click',
        async (event) => {

            const addButton =
                event.target.closest(
                    '.btn-add-to-cart'
                );


            if (addButton) {

                event.preventDefault();

                await handleAdd(
                    addButton
                );

                return;

            }


            const increase =
                event.target.closest(
                    '.btn-inc'
                );


            if (increase) {

                event.preventDefault();

                await handleIncrement(
                    increase
                );

                return;

            }


            const decrease =
                event.target.closest(
                    '.btn-dec'
                );


            if (decrease) {

                event.preventDefault();

                await handleDecrement(
                    decrease
                );

                return;

            }


            const remove =
                event.target.closest(
                    '.btn-remove'
                );


            if (remove) {

                event.preventDefault();

                await handleRemove(
                    remove
                );

                return;

            }


            const clear =
                event.target.closest(
                    '.btn-clear-cart'
                );


            if (clear) {

                event.preventDefault();

                await handleClear(
                    clear
                );

            }

        }
    );

}