import {
    getFavorites,
    clearFavorites
} from './storage';

export async function syncFavorites() {

    if (!window.App.isAuth) {
        return;
    }

    const favorites = getFavorites();

    if (favorites.length === 0) {
        return;
    }

    if (!window.App.routes.wishlist?.sync) {
        return;
    }

    try {

        const response = await fetch(
            window.App.routes.wishlist.sync,
            {
                method: 'POST',

                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': window.Laravel.csrfToken,
                },

                body: JSON.stringify({
                    favorites,
                }),
            }
        );

        const data = await response.json();

        if (data.ok) {
            clearFavorites();
        }

    } catch (error) {

        console.error('Wishlist Sync:', error);

    }

}