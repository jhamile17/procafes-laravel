// resources/js/modules/cart/events.js

import {
    addProduct,
    updateProduct,
    removeProduct,
    clearCart,
} from './api';

import {
    render,
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
    ALERTA EN TARJETA DEL PRODUCTO
=========================================================*/

function showButtonAlert(
    button,
    message
) {

    /*
    |--------------------------------------------------------------------------
    | Buscar la tarjeta del producto
    |--------------------------------------------------------------------------
    */

    const card =
        button.closest(
            '.product-card, .product-item, .card, [data-product-id]'
        );


    if (!card) {

        console.error(
            'No se encontró la tarjeta del producto.'
        );

        return;
    }


    /*
    |--------------------------------------------------------------------------
    | Buscar contenedor de alerta
    |--------------------------------------------------------------------------
    */

    let container =
        card.querySelector(
            '.product-alert-container'
        );


    /*
    |--------------------------------------------------------------------------
    | Si no existe, crearlo debajo del botón
    |--------------------------------------------------------------------------
    */

    if (!container) {

        container =
            document.createElement(
                'div'
            );

        container.className =
            'product-alert-container';


        button.insertAdjacentElement(
            'afterend',
            container
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Eliminar alerta anterior
    |--------------------------------------------------------------------------
    */

    const old =
        container.querySelector(
            '.product-alert'
        );


    if (old) {
        old.remove();
    }


    /*
    |--------------------------------------------------------------------------
    | Crear alerta
    |--------------------------------------------------------------------------
    */

    const alert =
        document.createElement(
            'div'
        );


    alert.className =
        'product-alert';


    alert.innerHTML = `

        <i class="bi bi-exclamation-triangle-fill"></i>

        <span></span>

    `;


    /*
    |--------------------------------------------------------------------------
    | Insertar mensaje de forma segura
    |--------------------------------------------------------------------------
    */

    const text =
        alert.querySelector(
            'span'
        );


    text.textContent =
        message;


    container.appendChild(
        alert
    );


    /*
    |--------------------------------------------------------------------------
    | Ocultar automáticamente
    |--------------------------------------------------------------------------
    */

    setTimeout(() => {

        alert.classList.add(
            'hide'
        );


        setTimeout(() => {

            if (alert.parentNode) {
                alert.remove();
            }

        }, 250);

    }, 3000);

}

/*=========================================================
    AGREGAR PRODUCTO
=========================================================*/

async function handleAdd(
    button
) {

    if (
        button.dataset.processing === 'true'
    ) {
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
        | AGREGADO CORRECTAMENTE
        |--------------------------------------------------------------------------
        */

        render(response);
        const productModal =
            button.closest(
                '.product-detail-modal'
            );

        if (productModal) {

            const modal =
                bootstrap.Modal.getInstance(
                    productModal
                );

            if (modal) {

                modal.hide();

            }

        }

        setTimeout(() => {

    showCart();

}, 300);


    } catch (error) {

        console.error(
            'Error agregando producto:',
            error
        );


        /*
        |--------------------------------------------------------------------------
        | IMPORTANTE:
        |
        | El error aparece en la tarjeta del producto.
        | NO abrimos el offcanvas.
        |--------------------------------------------------------------------------
        */

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


        /*
        |--------------------------------------------------------------------------
        | Si funciona, simplemente actualizamos el carrito.
        |--------------------------------------------------------------------------
        */

        render(response);


    } catch (error) {

        /*
        |--------------------------------------------------------------------------
        | NO MOSTRAR PRODUCT-ALERT AQUÍ
        |
        | Los errores del + pertenecen al carrito.
        |--------------------------------------------------------------------------
        */

        console.error(
            'Error aumentando cantidad:',
            error
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


        /*
        |--------------------------------------------------------------------------
        | Actualizar carrito
        |--------------------------------------------------------------------------
        */

        render(response);


    } catch (error) {

        /*
        |--------------------------------------------------------------------------
        | NO MOSTRAR PRODUCT-ALERT AQUÍ
        |--------------------------------------------------------------------------
        */

        console.error(
            'Error disminuyendo cantidad:',
            error
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


    } finally {

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
    CONTINUAR COMPRA
=========================================================*/

function handleCheckout(event) {

    const totalElement =
        document.getElementById(
            'cartPageTotal'
        );

    const emptyMessage =
        document.getElementById(
            'emptyCartMessage'
        );


    /*
    |--------------------------------------------------------------------------
    | Si no estamos en la página del carrito
    |--------------------------------------------------------------------------
    */

    if (!totalElement) {
        return;
    }


    /*
    |--------------------------------------------------------------------------
    | Obtener total actual
    |--------------------------------------------------------------------------
    */

    const totalText =
        totalElement.textContent
            .replace('S/', '')
            .replace(',', '')
            .trim();


    const total =
        parseFloat(totalText) || 0;


    /*
    |--------------------------------------------------------------------------
    | CARRITO VACÍO
    |--------------------------------------------------------------------------
    */

    if (total <= 0) {

        event.preventDefault();


        if (!emptyMessage) {
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Mostrar mensaje
        |--------------------------------------------------------------------------
        */

        emptyMessage.classList.remove(
            'd-none'
        );


        /*
        |--------------------------------------------------------------------------
        | Reiniciar animación
        |--------------------------------------------------------------------------
        */

        emptyMessage.classList.remove(
            'show'
        );

        void emptyMessage.offsetWidth;

        emptyMessage.classList.add(
            'show'
        );


        /*
        |--------------------------------------------------------------------------
        | Ocultar mensaje
        |--------------------------------------------------------------------------
        */

        clearTimeout(
            emptyMessage.hideTimeout
        );


        emptyMessage.hideTimeout =
            setTimeout(() => {

                emptyMessage.classList.remove(
                    'show'
                );


                setTimeout(() => {

                    emptyMessage.classList.add(
                        'd-none'
                    );

                }, 250);

            }, 3500);

    }

}

/*=========================================================
    LISTENER PRINCIPAL
=========================================================*/

export function initializeCartEvents() {

    document.addEventListener(
        'click',
        async (event) => {

            /*
            |--------------------------------------------------------------------------
            | CONTINUAR COMPRA
            |--------------------------------------------------------------------------
            */

            const checkoutButton =
                event.target.closest(
                    '#checkoutBtn'
                );


            if (checkoutButton) {

                handleCheckout(event);

                return;

            }


            /*
            |--------------------------------------------------------------------------
            | AGREGAR PRODUCTO
            |--------------------------------------------------------------------------
            */

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


            /*
            |--------------------------------------------------------------------------
            | INCREMENTAR
            |--------------------------------------------------------------------------
            */

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


            /*
            |--------------------------------------------------------------------------
            | DECREMENTAR
            |--------------------------------------------------------------------------
            */

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


            /*
            |--------------------------------------------------------------------------
            | ELIMINAR
            |--------------------------------------------------------------------------
            */

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


            /*
            |--------------------------------------------------------------------------
            | VACIAR CARRITO
            |--------------------------------------------------------------------------
            */

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