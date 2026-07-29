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
    data = null
) {

    const response = await fetch(url, {

        method,

        headers: {

            Accept: 'application/json',

            'X-CSRF-TOKEN': csrfToken(),

            ...(data
                ? {
                    'Content-Type': 'application/json'
                }
                : {})

        },

        body: data
            ? JSON.stringify(data)
            : null

    });

    if (!response.ok) {

        let message = `HTTP ${response.status}`;

        try {

            const json = await response.json();

            message = json.message ?? message;

        } catch (_) {}

        throw new Error(message);

    }

    return response.json();

}

/*
|--------------------------------------------------------------------------
| API del carrito
|--------------------------------------------------------------------------
*/

export function getCart() {

    return request(
        ROUTES.data
    );

}

export function addProduct(
    productId,
    quantity
) {

    return request(

        ROUTES.add,

        'POST',

        {
            product_id: productId,
            cantidad:quantity
        }

    );

}

export function updateProduct(
    productId,
    quantity
) {

    return request(

        `${ROUTES.base}/${productId}`,

        'PATCH',

        {
            cantidad:quantity
        }

    );

}

export function removeProduct(
    productId
) {

    return request(

        `${ROUTES.base}/${productId}`,

        'DELETE'

    );

}

export function clearCart() {

    return request(

        ROUTES.clear,

        'DELETE'

    );

}