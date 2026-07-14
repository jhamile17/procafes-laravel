import {
    getFavorites,
    toggleFavorite,
    clearFavorites
} from './storage';

import {
    toggleWishlist,
    syncWishlist
} from './api';

/*
|--------------------------------------------------------------------------
| Inicializar
|--------------------------------------------------------------------------
*/

document.addEventListener('DOMContentLoaded', async () => {

    /*
    |--------------------------------------------------------------------------
    | Sincronizar favoritos al iniciar sesión
    |--------------------------------------------------------------------------
    */

    if (window.App.isAuth) {

        const favorites = getFavorites();

        if (favorites.length > 0) {

            try {

                const response = await syncWishlist(favorites);

                if (response.ok) {

                    clearFavorites();

                }

            } catch (error) {

                console.error(error);

            }

        }

    }

    /*
    |--------------------------------------------------------------------------
    | Pintar corazones guardados
    |--------------------------------------------------------------------------
    */

    document
        .querySelectorAll('.product-wishlist')
        .forEach(button => {

            const productId = Number(
                button.dataset.productId
            );

            if (getFavorites().includes(productId)) {

                activate(button);

            }

        });

    updateBadge();

    /*
    |--------------------------------------------------------------------------
    | Eventos
    |--------------------------------------------------------------------------
    */

    document.addEventListener('click', async event => {

        const button = event.target.closest('.product-wishlist');

        if (!button) {
            return;
        }

        event.preventDefault();

        const productId = Number(
            button.dataset.productId
        );

        /*
        |--------------------------------------------------------------------------
        | Usuario invitado
        |--------------------------------------------------------------------------
        */

        if (!window.App.isAuth) {

            const active = toggleFavorite(productId);

            update(button, active);

            updateBadge();

            animateBadge('wishlistBadge');
           showWishlistMessage(
            active
        );


            return;

        }

        /*
        |--------------------------------------------------------------------------
        | Usuario autenticado
        |--------------------------------------------------------------------------
        */

        try {

            const response = await toggleWishlist(productId);

            if (!response.ok) {
                return;
            }

            update(
                button,
                response.added
            );

            updateBadge();

            animateBadge('wishlistBadge');
            showWishlistMessage(
                response.added
            );

        } catch (error) {

            console.error(error);

        }

    });

});

/*
|--------------------------------------------------------------------------
| Helpers
|--------------------------------------------------------------------------
*/

function activate(button) {

    update(button, true);

}

function update(button, active) {

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

}

/*
|--------------------------------------------------------------------------
| Badge
|--------------------------------------------------------------------------
*/

function updateBadge() {

    const badge = document.getElementById(
        'wishlistBadge'
    );

    if (!badge) {
        return;
    }

    const total = getFavorites().length;

    badge.textContent = total;

    badge.style.display = total > 0
        ? 'inline-flex'
        : 'none';

}

/*
|--------------------------------------------------------------------------
| Animación Badge
|--------------------------------------------------------------------------
*/

function animateBadge(id) {

    const badge = document.getElementById(id);

    if (!badge) {
        return;
    }

    badge.classList.remove('badge-pop');

    void badge.offsetWidth;

    badge.classList.add('badge-pop');

}
function showWishlistMessage(added) {

    const message = document.getElementById('wishlistMessage');

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