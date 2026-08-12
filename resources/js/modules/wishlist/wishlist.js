import {
    getWishlist,
    getWishlistCount,
    toggleWishlist
} from './api';
import { addProduct } from '../cart/api';
import {
    initializeIcons,
    updateIcon
} from './ui';

import {
    getWishlistBadge,
    getWishlistMessage
} from './dom';

/*==========================================================================
    Inicializar
==========================================================================*/

document.addEventListener('DOMContentLoaded', async () => {

    try {

        const response = await getWishlist();

        initializeIcons(response.items);

        updateBadge(response.count);

    } catch (error) {

        console.error('Wishlist:', error);

    }

});

/*==========================================================================
    Toggle favorito
==========================================================================*/

document.addEventListener('click', async event => {

    const button = event.target.closest('.product-wishlist');

    if (!button) {
        return;
    }

    event.preventDefault();

    const productId = Number(
        button.dataset.productId
    );

    try {

        const response = await toggleWishlist(productId);

        if (!response.ok) {
            return;
        }

        updateIcon(
            button,
            response.added
        );

        updateBadge(
            response.count
        );

        animateBadge();

        showWishlistMessage(
            response.added
        );
        

    } catch (error) {

        console.error('Wishlist:', error);

    }

});
document.addEventListener('click', async event => {

    const button = event.target.closest('.wishlist-remove');

    if (!button) {
        return;
    }

    event.preventDefault();

    const productId = Number(button.dataset.product);

    try {

        const response = await toggleWishlist(productId);

        if (!response.ok) {
            return;
        }

        // Eliminar la tarjeta de la vista
        button.closest('.wishlist-card')?.remove();

        // Actualizar badge
        updateBadge(response.count);

        animateBadge();

        showWishlistMessage(false);

    } catch (error) {

        console.error(error);

    }

});
document.addEventListener('click', async event => {

    const button = event.target.closest('.wishlist-cart');

    if (!button) {
        return;
    }

    event.preventDefault();

    const productId = Number(button.dataset.product);

    try {

        const cart = await addProduct(productId);

        if (window.Cart) {
            await window.Cart.update(cart);
        }
        showSuccessAlert(
            button,
            'Producto agregado al carrito.'
        );

    } catch (error) {

        console.error(error);

    }

});
/*==========================================================================
    Badge
==========================================================================*/

function updateBadge(total)
{
    const badge = getWishlistBadge();

    if (!badge) {
        return;
    }

    badge.textContent = total;

    badge.style.display =
        total > 0
            ? 'inline-flex'
            : 'none';
}

/*==========================================================================
    Animación Badge
==========================================================================*/

function animateBadge()
{
    const badge = getWishlistBadge();

    if (!badge) {
        return;
    }

    badge.classList.remove('badge-pop');

    void badge.offsetWidth;

    badge.classList.add('badge-pop');
}

/*==========================================================================
    Mensaje
==========================================================================*/

function showWishlistMessage(added)
{
    const message = getWishlistMessage();

    if (!message) {
        return;
    }

    message.innerHTML = added
        ? '<i class="bi bi-heart-fill"></i>'
        : '<i class="bi bi-heartbreak-fill"></i>';

    message.classList.remove('show');

    void message.offsetWidth;

    message.classList.add('show');
}
function showSuccessAlert(button, message) {

    const parent = button.closest('.wishlist-actions');

    const old = parent.querySelector('.wishlist-toast');

    if (old) {
        old.remove();
    }

    const toast = document.createElement('div');

    toast.className = 'wishlist-toast';

    toast.innerHTML = `
        <i class="bi bi-check-circle-fill"></i>
        <span>${message}</span>
    `;

    parent.appendChild(toast);

    requestAnimationFrame(() => {
        toast.classList.add('show');
    });

    setTimeout(() => {

        toast.classList.remove('show');

        setTimeout(() => {
            toast.remove();
        }, 250);

    }, 1800);
}