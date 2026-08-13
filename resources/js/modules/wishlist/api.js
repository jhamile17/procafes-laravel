export async function getWishlist() {

    const response = await fetch(
        window.Laravel.routes.wishlist.index,
        {
            headers: {
                'Accept': 'application/json',
            },
        }
    );

    const data = await response.json();

    if (!response.ok) {
        throw data;
    }

    return data;

}

/*==========================================================================
    Agregar / Eliminar favorito
==========================================================================*/

export async function toggleWishlist(productId) {

    const response = await fetch(
        window.Laravel.routes.wishlist.toggle,
        {
            method: 'POST',

            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': window.Laravel.csrfToken,
            },

            body: JSON.stringify({
                product_id: Number(productId),
            }),
        }
    );

    const data = await response.json();

    if (!response.ok) {
        throw data;
    }

    return data;

}

/*==========================================================================
    Obtener contador
==========================================================================*/

export async function getWishlistCount() {

    const response = await fetch(
        window.Laravel.routes.wishlist.count,
        {
            headers: {
                'Accept': 'application/json',
            },
        }
    );

    const data = await response.json();

    if (!response.ok) {
        throw data;
    }

    return data;

}

/*==========================================================================
    Vaciar favoritos
==========================================================================*/

export async function clearWishlist() {

    const response = await fetch(
        window.Laravel.routes.wishlist.clear,
        {
            method: 'DELETE',

            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': window.Laravel.csrfToken,
            },
        }
    );

    const data = await response.json();

    if (!response.ok) {
        throw data;
    }

    return data;

}