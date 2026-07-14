import { getFavorites } from './storage';

import { getWishlistButtons } from './dom';

/*
|--------------------------------------------------------------------------
| Inicializar iconos
|--------------------------------------------------------------------------
*/

export function initializeIcons() {

    const favorites = getFavorites();

    getWishlistButtons().forEach(button => {

        const productId = Number(
            button.dataset.productId
        );

        updateIcon(
            button,
            favorites.includes(productId)
        );

    });

}

/*
|--------------------------------------------------------------------------
| Actualizar icono
|--------------------------------------------------------------------------
*/

export function updateIcon(button, active) {

    const icon = button.querySelector('i');

    if (!icon) {
        return;
    }

    button.classList.toggle(
        'active',
        active
    );

    icon.classList.toggle(
        'bi-heart',
        !active
    );

    icon.classList.toggle(
        'bi-heart-fill',
        active
    );

    button.setAttribute(
        'aria-pressed',
        active
    );

}