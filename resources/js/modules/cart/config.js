// resources/js/modules/cart/config.js

const ROUTES = window.Laravel?.routes?.cart || {};

const APP = {

    isAuth: window.Laravel?.auth ?? false

};

const MAX_QTY = 8;

export {

    ROUTES,
    APP,
    MAX_QTY

};