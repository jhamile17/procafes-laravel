import { MAX_QTY } from './config';

import {
    addProduct,
    updateProduct,
    removeProduct,
    clearCart
} from './api';

function showButtonAlert(button, message) {

    const old = button.parentNode.querySelector('.product-alert');

    if (old) {
        old.remove();
    }

    const alert = document.createElement('div');

    alert.className = 'product-alert';

    alert.innerHTML = `
        <i class="bi bi-exclamation-triangle-fill"></i>
        <span>${message}</span>
    `;

    button.insertAdjacentElement('afterend', alert);

    setTimeout(() => {

        alert.classList.add('hide');

        setTimeout(() => {

            alert.remove();

        }, 250);

    }, 2500);

}
/*Mostrar Offcanvas*/
function showCart() {
    
    if (document.body.classList.contains('cart-page')) {
        return;
    }

    const offcanvas = document.getElementById('cartOffcanvas');
    
    if (!offcanvas || typeof bootstrap === 'undefined') {
        return;
    }

    bootstrap.Offcanvas
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

        const html = button.innerHTML;

        button.innerHTML = `
            <span class="spinner-border spinner-border-sm me-2"></span>
            Agregando...
        `;

        try {
            const cart = await addProduct(
                Number(button.dataset.productId),
                Number(button.dataset.quantity ?? 1)
            );

            onUpdate(cart);
            showCart();

        }

        catch (error) {

            console.error(error);
            if(
                error.message &&
                error.message.includes('8 unidades')
            ){
                showCart();
                showButtonAlert(
                    button,
                    error.message
                 );
            return;
        }
         alert(
            error.message || 'No se puo agregar el producto'
         );
        }

        finally {

            button.disabled = false;

            button.innerHTML = html;

        }

    });

}

/*
|--------------------------------------------------------------------------
| Incrementar
|--------------------------------------------------------------------------
*/

async function increment(button, onUpdate) {

    const id = Number(button.dataset.productId);

    const quantity = Number(button.dataset.quantity) + 1;
    try{
        const cart = await updateProduct(id, quantity);
         await onUpdate(cart);
        }
        catch (error){
            console.error(error);
            showButtonAlert(
                button,
                error.message || 'Solo puedes comprar hasta 8 unidades de este producto.'
            );
            
        
}
}
/*
|--------------------------------------------------------------------------
| Disminuir
|--------------------------------------------------------------------------
*/

async function decrement(button, onUpdate) {

    const id = Number(button.dataset.productId);

    const quantity = Number(button.dataset.quantity) - 1;

    if (quantity < 1) {
        return;
    }

    const cart = await updateProduct(id, quantity);

    await onUpdate(cart);

}

/*
|--------------------------------------------------------------------------
| Eliminar
|--------------------------------------------------------------------------
*/

async function remove(button, onUpdate) {

    const cart = await removeProduct(
        Number(button.dataset.productId)
    );

    await onUpdate(cart);

}

/*
|--------------------------------------------------------------------------
| Eventos del carrito
|--------------------------------------------------------------------------
*/

export function bindCartActions(onUpdate) {

    document.addEventListener('click', async (e) => {

        const inc = e.target.closest('.btn-inc');
        const dec = e.target.closest('.btn-dec');
        const del = e.target.closest('.btn-remove');

        try {

            if (inc) {

                await increment(inc, onUpdate);

                return;

            }

            if (dec) {

                await decrement(dec, onUpdate);

                return;

            }

            if (del) {

                await remove(del, onUpdate);

            }

        }

        catch (error) {

            console.error(error);

        }

    });

}

/*
|--------------------------------------------------------------------------
| Vaciar carrito
|--------------------------------------------------------------------------
*/

export function bindClearCart(onUpdate) {

    document.addEventListener('click', async (e) => {

        const button = e.target.closest('.btn-clear-cart');

        if (!button) {
            return;
        }

        e.preventDefault();
        button.disabled = true;

        try {

            const cart = await clearCart();

            await onUpdate(cart);

        }

        catch (error) {

            console.error(error);

        }

        finally {

            button.disabled = false;

        }

    });

}
