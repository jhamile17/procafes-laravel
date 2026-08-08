// resources/js/cart/api.js

import { ROUTES } from './config';
import { csrfToken } from './helpers';

/*
|--------------------------------------------------------------------------
| Petición HTTP
|--------------------------------------------------------------------------
*/

async function request(
    url,
    method = 'GET',
    data = null,
    responseType = 'json'
) {

    const response = await fetch(url, {
        method,

        credentials: 'same-origin',

        cache: 'no-store',

        headers: {
            Accept: responseType === 'text'
                ? 'text/html'
                : 'application/json',

            'X-CSRF-TOKEN': csrfToken(),

            ...(data
                ? {
                    'Content-Type': 'application/json',
                }
                : {}),
        },

        body: data
            ? JSON.stringify(data)
            : null,
    });

    if (!response.ok) {

        let message = `HTTP ${response.status}`;

        try {

            const error = await response.json();

            message = error.message ?? message;

        } catch (_) {}

        throw new Error(message);

    }

    return responseType === 'text'
        ? await response.text()
        : await response.json();

}

/*
|--------------------------------------------------------------------------
| Recomendaciones
|--------------------------------------------------------------------------
*/

export function getRecommendations() {

    return request(
        ROUTES.recommendations,
        'GET',
        null,
        'text'
    );

}

/*
|--------------------------------------------------------------------------
| Obtener carrito
|--------------------------------------------------------------------------
*/

export function getCart() {

    return request(
        ROUTES.data
    );

}

/*
|--------------------------------------------------------------------------
| Agregar producto
|--------------------------------------------------------------------------
*/

export function addProduct(
    productId,
    quantity = 1
) {

    return request(
        ROUTES.add,
        'POST',
        {
            product_id: productId,
            cantidad: quantity,
        }
    );

}

/*
|--------------------------------------------------------------------------
| Actualizar cantidad
|--------------------------------------------------------------------------
*/

export function updateProduct(
    productId,
    quantity
) {

    return request(
        `${ROUTES.base}/${productId}`,
        'PATCH',
        {
            cantidad: quantity,
        }
    );

}

/*
|--------------------------------------------------------------------------
| Eliminar producto
|--------------------------------------------------------------------------
*/

export function removeProduct(
    productId
) {

    return request(
        `${ROUTES.base}/${productId}`,
        'DELETE'
    );

}

/*
|--------------------------------------------------------------------------
| Vaciar carrito
|--------------------------------------------------------------------------
*/

export function clearCart() {

    return request(
        ROUTES.clear,
        'DELETE'
    );

}