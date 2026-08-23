import {
    getWishlist,
    toggleWishlist
} from './api';

import {
    addProduct
} from '../cart/api';

import {
    initializeIcons,
    updateIcon
} from './ui';

import {
    getWishlistBadge,
    getWishlistMessage
} from './dom';


/*==========================================================================
    INICIALIZAR WISHLIST
==========================================================================*/

document.addEventListener(
    'DOMContentLoaded',
    async () => {

        try {

            const response =
                await getWishlist();


            initializeIcons(
                response.items
            );


            updateBadge(
                response.count
            );


        } catch (error) {

            console.error(
                'Wishlist:',
                error
            );

        }

    }
);


/*==========================================================================
    TOGGLE FAVORITO DESDE PRODUCTOS
==========================================================================*/

document.addEventListener(
    'click',
    async event => {

        const button =
            event.target.closest(
                '.product-wishlist'
            );


        if (!button) {
            return;
        }


        event.preventDefault();


        const productId =
            Number(
                button.dataset.productId
            );


        if (!productId) {
            return;
        }


        try {

            const response =
                await toggleWishlist(
                    productId
                );


            if (!response.ok) {
                return;
            }


            updateIcon(
                button,
                response.added
            );


            updateBadge(
                response.count
            );


            animateBadge();


            showWishlistMessage(
                response.added
            );


        } catch (error) {

            console.error(
                'Wishlist:',
                error
            );

        }

    }
);


/*==========================================================================
    ELIMINAR DE FAVORITOS
==========================================================================*/

document.addEventListener(
    'click',
    async event => {

        const button =
            event.target.closest(
                '.wishlist-remove'
            );


        if (!button) {
            return;
        }


        event.preventDefault();


        if (button.disabled) {
            return;
        }


        const productId =
            Number(
                button.dataset.product
            );


        if (!productId) {
            return;
        }


        button.disabled =
            true;


        try {

            const response =
                await toggleWishlist(
                    productId
                );


            if (!response.ok) {

                button.disabled =
                    false;

                return;

            }


            const card =
                button.closest(
                    '.wishlist-card'
                );


            /*
            |--------------------------------------------------------------------------
            | Animación de eliminación manual
            |--------------------------------------------------------------------------
            */

            if (card) {

                card.style.transition =
                    'opacity .25s ease, transform .25s ease';

                card.style.opacity =
                    '0';

                card.style.transform =
                    'translateX(20px)';


                setTimeout(() => {

                    card.remove();


                    updateWishlistAfterChange(
                        response.count
                    );


                }, 250);


            } else {

                updateWishlistAfterChange(
                    response.count
                );

            }


            showWishlistMessage(
                false
            );


        } catch (error) {

            console.error(
                'Error eliminando favorito:',
                error
            );


            button.disabled =
                false;

        }

    }
);


/*==========================================================================
    AGREGAR FAVORITO AL CARRITO
==========================================================================*/

document.addEventListener(
    'click',
    async event => {

        const button =
            event.target.closest(
                '.wishlist-cart'
            );


        if (!button) {
            return;
        }


        event.preventDefault();


        /*
        |--------------------------------------------------------------------------
        | Evitar doble clic
        |--------------------------------------------------------------------------
        */

        if (button.disabled) {
            return;
        }


        const productId =
            Number(
                button.dataset.product
            );


        if (!productId) {
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Deshabilitar botón
        |--------------------------------------------------------------------------
        */

        button.disabled =
            true;


        const originalHtml =
            button.innerHTML;


        button.innerHTML = `

            <span
                class="spinner-border spinner-border-sm"
                aria-hidden="true">
            </span>

        `;


        try {

            /*
            |--------------------------------------------------------------------------
            | AGREGAR EXACTAMENTE UNA UNIDAD
            |--------------------------------------------------------------------------
            */

            const cart =
                await addProduct(
                    productId,
                    1
                );


            /*
            |--------------------------------------------------------------------------
            | ACTUALIZAR CARRITO
            |--------------------------------------------------------------------------
            */

            if (
                window.Cart &&
                typeof window.Cart.update === 'function'
            ) {

                await window.Cart.update(
                    cart
                );

            }


            /*
            |--------------------------------------------------------------------------
            | ELIMINAR DE WISHLIST EN BASE DE DATOS
            |--------------------------------------------------------------------------
            |
            | Esto evita que vuelva a aparecer al refrescar.
            |--------------------------------------------------------------------------
            */

            const wishlistResponse =
                await toggleWishlist(
                    productId
                );


            if (!wishlistResponse.ok) {

                console.error(
                    'No se pudo eliminar el producto de Wishlist.'
                );


                button.disabled =
                    false;


                button.innerHTML =
                    originalHtml;


                return;

            }


            /*
            |--------------------------------------------------------------------------
            | RESTAURAR BOTÓN INMEDIATAMENTE
            |--------------------------------------------------------------------------
            */

            button.disabled =
                false;


            button.innerHTML =
                originalHtml;


            /*
            |--------------------------------------------------------------------------
            | MOSTRAR MENSAJE
            |--------------------------------------------------------------------------
            */

            showSuccessAlert(
                button,
                'Se agregó al carrito'
            );


            /*
            |--------------------------------------------------------------------------
            | BUSCAR TARJETA
            |--------------------------------------------------------------------------
            */

            const card =
                button.closest(
                    '.wishlist-card'
                );


            /*
            |--------------------------------------------------------------------------
            | MANTENER PRODUCTO VISIBLE
            |--------------------------------------------------------------------------
            |
            | El mensaje permanece visible durante 3 segundos.
            |--------------------------------------------------------------------------
            */

            if (card) {

                setTimeout(() => {

                    /*
                    |--------------------------------------------------------------------------
                    | Comenzar salida
                    |--------------------------------------------------------------------------
                    */

                    card.style.transition =
                        'opacity .5s ease, transform .5s ease';

                    card.style.opacity =
                        '0';

                    card.style.transform =
                        'translateX(20px)';


                    /*
                    |--------------------------------------------------------------------------
                    | Eliminar después de terminar animación
                    |--------------------------------------------------------------------------
                    */

                    setTimeout(() => {

                        if (card.parentNode) {

                            card.remove();

                        }


                        updateWishlistAfterCart(
                            wishlistResponse.count
                        );


                    }, 500);


                }, 3000);


            } else {

                updateWishlistAfterCart(
                    wishlistResponse.count
                );

            }


        } catch (error) {

            console.error(
                'Error agregando favorito al carrito:',
                error
            );


            /*
            |--------------------------------------------------------------------------
            | Restaurar botón si ocurre un error
            |--------------------------------------------------------------------------
            */

            button.disabled =
                false;


            button.innerHTML =
                originalHtml;

        }

    }
);


/*==========================================================================
    ACTUALIZAR WISHLIST DESPUÉS DE ELIMINAR
==========================================================================*/

function updateWishlistAfterChange(
    total
) {

    updateBadge(
        total
    );


    animateBadge();


    if (total === 0) {

        showEmptyWishlist();

    }

}


/*==========================================================================
    ACTUALIZAR WISHLIST DESPUÉS DE AGREGAR AL CARRITO
==========================================================================*/

function updateWishlistAfterCart(
    total
) {

    /*
    |--------------------------------------------------------------------------
    | Usar contador REAL del servidor
    |--------------------------------------------------------------------------
    */

    updateBadge(
        total
    );


    animateBadge();


    /*
    |--------------------------------------------------------------------------
    | Si ya no quedan favoritos
    |--------------------------------------------------------------------------
    */

    if (total === 0) {

        showEmptyWishlist();

    }

}


/*==========================================================================
    MOSTRAR WISHLIST VACÍA
==========================================================================*/

function showEmptyWishlist()
{

    const body =
        document.querySelector(
            '.wishlist-body'
        );


    if (!body) {
        return;
    }


    body.innerHTML = `

        <div class="wishlist-empty">

            <i class="bi bi-heart"></i>


            <h3>
                Aún no tienes favoritos
            </h3>


            <p>
                Explora nuestro catálogo y guarda
                los productos que más te gusten.
            </p>


            <a
                href="/products"
                class="wishlist-empty-btn">

                <i class="bi bi-shop"></i>


                <span>
                    Ir al catálogo
                </span>

            </a>

        </div>

    `;

}


/*==========================================================================
    BADGE
==========================================================================*/

function updateBadge(
    total
)
{

    const badge =
        getWishlistBadge();


    if (!badge) {
        return;
    }


    /*
    |--------------------------------------------------------------------------
    | Mostrar solamente la cantidad
    |--------------------------------------------------------------------------
    */

    badge.textContent =
        total;


    badge.style.display =
        total > 0
            ? 'inline-flex'
            : 'none';

}


/*==========================================================================
    ANIMACIÓN DEL BADGE
==========================================================================*/

function animateBadge()
{

    const badge =
        getWishlistBadge();


    if (!badge) {
        return;
    }


    badge.classList.remove(
        'badge-pop'
    );


    void badge.offsetWidth;


    badge.classList.add(
        'badge-pop'
    );

}


/*==========================================================================
    MENSAJE WISHLIST
==========================================================================*/

function showWishlistMessage(
    added
)
{

    const message =
        getWishlistMessage();


    if (!message) {
        return;
    }


    message.innerHTML =
        added
            ? '<i class="bi bi-heart-fill"></i>'
            : '<i class="bi bi-heartbreak-fill"></i>';


    message.classList.remove(
        'show'
    );


    void message.offsetWidth;


    message.classList.add(
        'show'
    );

}


/*==========================================================================
    MENSAJE PRODUCTO AGREGADO AL CARRITO
==========================================================================*/

function showSuccessAlert(
    button,
    message
) {

    const parent =
        button.closest(
            '.wishlist-actions'
        );


    if (!parent) {
        return;
    }


    /*
    |--------------------------------------------------------------------------
    | Eliminar mensaje anterior
    |--------------------------------------------------------------------------
    */

    const old =
        parent.querySelector(
            '.wishlist-toast'
        );


    if (old) {
        old.remove();
    }


    /*
    |--------------------------------------------------------------------------
    | Crear mensaje
    |--------------------------------------------------------------------------
    */

    const toast =
        document.createElement(
            'div'
        );


    toast.className =
        'wishlist-toast';


    toast.innerHTML = `

        <i class="bi bi-check-circle-fill"></i>

        <span>
            ${message}
        </span>

    `;


    /*
    |--------------------------------------------------------------------------
    | Insertar cerca del botón del carrito
    |--------------------------------------------------------------------------
    */

    parent.appendChild(
        toast
    );


    /*
    |--------------------------------------------------------------------------
    | Mostrar
    |--------------------------------------------------------------------------
    */

    requestAnimationFrame(() => {

        toast.classList.add(
            'show'
        );

    });


    /*
    |--------------------------------------------------------------------------
    | Mantener visible durante 3 segundos
    |--------------------------------------------------------------------------
    */

    setTimeout(() => {

        toast.classList.remove(
            'show'
        );


        setTimeout(() => {

            if (toast.parentNode) {

                toast.remove();

            }

        }, 300);


    }, 3000);

}