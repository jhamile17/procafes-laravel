// resources/js/cart/helpers.js

/*
|--------------------------------------------------------------------------
| CSRF Token
|--------------------------------------------------------------------------
*/

function csrfToken() {

    return document
        .querySelector('meta[name="csrf-token"]')
        ?.getAttribute('content') ?? '';

}

/*
|--------------------------------------------------------------------------
| Formato de moneda
|--------------------------------------------------------------------------
*/

function currency(value) {

    value = Number(value) || 0;

    return new Intl.NumberFormat(
        'es-PE',
        {
            style: 'currency',
            currency: 'PEN'
        }
    ).format(value);

}

export {
    csrfToken,
    currency
};