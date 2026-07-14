const STORAGE_KEY = 'procafes_favorites';

export function getFavorites() {

    return JSON.parse(
        localStorage.getItem(STORAGE_KEY) ?? '[]'
    );

}

export function saveFavorites(favorites) {

    localStorage.setItem(
        STORAGE_KEY,
        JSON.stringify(favorites)
    );

}

export function toggleFavorite(productId) {

    const favorites = getFavorites();

    const index = favorites.indexOf(productId);

    if (index === -1) {

        favorites.push(productId);

        saveFavorites(favorites);

        return true;

    }

    favorites.splice(index, 1);

    saveFavorites(favorites);

    return false;

}

export function clearFavorites() {

    localStorage.removeItem(STORAGE_KEY);

}