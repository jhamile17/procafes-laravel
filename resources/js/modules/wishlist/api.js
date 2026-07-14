export async function toggleWishlist(productId) {

    const response = await fetch(
        window.App.routes.wishlist.toggle,
        {
            method: 'POST',

            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': window.Laravel.csrfToken,
            },

            body: JSON.stringify({
                product_id: productId,
            }),
        }
    );

    return response.json();

}

export async function syncWishlist(favorites) {

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

    return response.json();

}