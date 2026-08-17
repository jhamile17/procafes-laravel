// resources/js/modules/cart/api.js

import { ROUTES } from './config';
import { csrfToken } from './helpers';


/*=========================================================
    PETICIÓN HTTP
=========================================================*/

async function request(
    url,
    method = 'GET',
    data = null,
    responseType = 'json'
) {

    const options = {
        method,
        credentials: 'same-origin',
        cache: 'no-store',

        headers: {
            Accept:
                responseType === 'text'
                    ? 'text/html'
                    : 'application/json',

            'X-Requested-With': 'XMLHttpRequest',

            'X-CSRF-TOKEN': csrfToken(),
        },
    };


    if (data !== null) {

        options.headers['Content-Type'] =
            'application/json';

        options.body =
            JSON.stringify(data);

    }


    const response =
        await fetch(url, options);


    let result;


    try {

        result =
            responseType === 'text'
                ? await response.text()
                : await response.json();

    } catch (error) {

        throw new Error(
            'El servidor devolvió una respuesta inválida.'
        );

    }


    if (!response.ok) {

        const message =
            result?.message ??
            result?.error ??
            `HTTP ${response.status}`;

        throw new Error(message);

    }


    return result;

}


/*=========================================================
    OBTENER CARRITO
=========================================================*/

export async function getCart() {

    return request(
        ROUTES.data,
        'GET'
    );

}


/*=========================================================
    AGREGAR PRODUCTO
=========================================================*/

export async function addProduct(
    productId,
    quantity = 1
) {

    return request(
        ROUTES.add,
        'POST',
        {
            product_id: Number(productId),
            cantidad: Number(quantity),
        }
    );

}


/*=========================================================
    ACTUALIZAR PRODUCTO
=========================================================*/

export async function updateProduct(
    productId,
    quantity
) {

    return request(
        `${ROUTES.base}/${productId}`,
        'PATCH',
        {
            cantidad: Number(quantity),
        }
    );

}


/*=========================================================
    ELIMINAR PRODUCTO
=========================================================*/

export async function removeProduct(
    productId
) {

    return request(
        `${ROUTES.base}/${productId}`,
        'DELETE'
    );

}


/*=========================================================
    VACIAR CARRITO
=========================================================*/

export async function clearCart() {

    return request(
        ROUTES.clear,
        'DELETE'
    );

}


/*=========================================================
    RECOMENDACIONES
=========================================================*/

export async function getRecommendations() {

    return request(
        ROUTES.recommendations,
        'GET',
        null,
        'text'
    );

}