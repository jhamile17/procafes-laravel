import {
    getWishlist,
    getWishlistCount,
    toggleWishlist
} from './api';

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