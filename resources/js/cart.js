// resources/js/cart.js
window.addEventListener('DOMContentLoaded', () => {
    const ROUTES = window.Laravel?.routes || {};
    const APP = window.App || {
        isAuth: false,
        routes: {}
    };

    const MAX_QTY = 8;
    const badge = document.getElementById('cartBadge');
    const itemsBox = document.getElementById('cartItems');
    const totalBox = document.getElementById('cartTotal');
    const offcanvasEl = document.getElementById('cartOffcanvas');
    const offcanvas = offcanvasEl
        ? new bootstrap.Offcanvas(offcanvasEl)
        : null;

    function csrfToken() {
        return document
            .querySelector('meta[name="csrf-token"]')
            ?.getAttribute('content') ?? '';
    }

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

    async function api(
        url,
        method = 'GET',
        data = null
    ) {
        const response = await fetch(url, {
            method,
            headers: {
                'Accept': 'application/json',
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
        return await response.json();
    }
        function render(cart) {

    if (badge) {
        badge.textContent = cart.count ?? 0;
    }

    if (totalBox) {
        totalBox.textContent = currency(cart.total ?? 0);
    }

    if (!itemsBox) {
        return;
    }
    
    const clearBtn = document.getElementById('btnClearCart');

    itemsBox.innerHTML = '';

    const items = Array.isArray(cart.items)
        ? cart.items
        : Object.values(cart.items || {});

    if (items.length === 0) {

        itemsBox.innerHTML = `
            <div class="text-center py-5 text-muted">
                <i class="bi bi-cart-x fs-2 d-block mb-2"></i>
                Tu carrito está vacío.
            </div>
        `;

        if (clearBtn) {
            clearBtn.classList.add('d-none');
        }

        return;
    }

    if (clearBtn) {
        clearBtn.classList.remove('d-none');
    }

    items.forEach(item => {

        const product = item.product ?? {};

        const id = item.product_id;

        const name = product.name ?? item.name ?? '';

        const image =
            product.image_url ??
            item.image ??
            '/images/no-image.png';

        const price = Number(item.price ?? 0);

        const quantity = Number(item.quantity ?? 1);

        const subtotal = price * quantity;

        const div = document.createElement('div');

        div.className = 'list-group-item py-3';

        div.innerHTML = `
            <div class="d-flex gap-3">

                <img
                    src="${image}"
                    class="rounded"
                    width="60"
                    height="60"
                    style="object-fit:cover"
                >

                <div class="flex-grow-1">

                    <div class="fw-semibold">
                        ${name}
                    </div>

                    <small class="text-muted">
                        ${currency(price)}
                    </small>

                    <div class="d-flex justify-content-between align-items-center mt-2">

                        <div class="btn-group btn-group-sm">

                            <button
                                class="btn btn-outline-secondary btn-dec"
                                data-id="${id}"
                                ${quantity <= 1 ? 'disabled' : ''}>
                                -
                            </button>

                            <button
                                class="btn btn-light disabled">
                                ${quantity}
                            </button>

                            <button
                                class="btn btn-outline-secondary btn-inc"
                                data-id="${id}"
                                ${quantity >= MAX_QTY ? 'disabled' : ''}>
                                +
                            </button>

                        </div>

                        <strong>
                            ${currency(subtotal)}
                        </strong>

                        <button
                            class="btn btn-sm btn-outline-danger btn-remove"
                            data-id="${id}">
                            <i class="bi bi-trash"></i>
                        </button>

                    </div>

                </div>

            </div>
        `;

        itemsBox.appendChild(div);

    });

}
        /*
    |--------------------------------------------------------------------------
    | Toast
    |--------------------------------------------------------------------------
    */

    function showToast({

        title = 'Listo',

        body = '',

        delay = 2500

    }) {

        const container = document.getElementById('toastContainer');

        if (!container) {
            return;
        }

        const toast = document.createElement('div');

        toast.className =
            'toast align-items-center border-0 shadow';

        toast.innerHTML = `
            <div class="toast-header">
                <strong class="me-auto">
                    ${title}
                </strong>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="toast">
                </button>
            </div>

            <div class="toast-body">
                ${body}
            </div>
        `;

        container.appendChild(toast);

        const bsToast = new bootstrap.Toast(
            toast,
            {
                delay
            }
        );

        bsToast.show();

        toast.addEventListener(
            'hidden.bs.toast',
            () => toast.remove()
        );

    }

    /*
    |--------------------------------------------------------------------------
    | Refrescar carrito
    |--------------------------------------------------------------------------
    */

    async function refreshCart() {

        if (!ROUTES.index) {
            return;
        }

        try {

            const cart = await api(
                ROUTES.index
            );
            console.log('CART=>',cart)

            render(cart);

        } catch (error) {

            console.error(
                '[CART]',
                error
            );

        }

    }

    window.refreshCart = refreshCart;

    refreshCart();
    document
    .getElementById('btnClearCart')
    ?.addEventListener('click', async (e) => {

        e.preventDefault();

        try {

            const cart = await api(
                ROUTES.clear,
                'DELETE'
            );

            render(cart);

        } catch (error) {

            console.error(error);

            showToast({
                title: 'Error',
                body: error.message
            });

        }

    });
        /*
    |--------------------------------------------------------------------------
    | Agregar producto
    |--------------------------------------------------------------------------
    */

    document.addEventListener('click', async (e) => {

        const btn = e.target.closest('.btn-add-to-cart');

        if (!btn || !ROUTES.add) {
            return;
        }

        e.preventDefault();

        const productId = parseInt(
            btn.dataset.id,
            10
        );

        const cantidad = Math.max(
            1,
            Math.min(
                MAX_QTY,
                parseInt(btn.dataset.qty || '1', 10)
            )
        );

        const htmlOriginal = btn.innerHTML;

        btn.disabled = true;

        btn.innerHTML = `
            <span
                class="spinner-border spinner-border-sm me-2">
            </span>
            Agregando...
        `;

        try {

            const cart = await api(

                ROUTES.add,

                'POST',

                {
                    product_id: productId,
                    cantidad
                }

            );

            render(cart);

            offcanvas?.show();

        } catch (error) {

            console.error(
                '[CART]',
                error
            );

            showToast({

                title: 'Error',

                body: error.message

            });

        } finally {

            btn.disabled = false;

            btn.innerHTML = htmlOriginal;

        }

    });
    /*
    |--------------------------------------------------------------------------
    | Acciones del carrito
    |--------------------------------------------------------------------------
    */

    itemsBox?.addEventListener('click', async (e) => {

        const inc = e.target.closest('.btn-inc');
        const dec = e.target.closest('.btn-dec');
        const remove = e.target.closest('.btn-remove');

        try {

            /*
            |--------------------------------------------------------------
            | Aumentar / Disminuir
            |--------------------------------------------------------------
            */

            if (inc || dec) {

                const id = (inc || dec).dataset.id;

                const input = (inc || dec)
                    .parentElement
                    .querySelector('.qty-input');

                let cantidad = parseInt(input.value);

                if (inc) {

                    cantidad = Math.min(
                        MAX_QTY,
                        cantidad + 1
                    );

                } else {

                    cantidad = Math.max(
                        1,
                        cantidad - 1
                    );

                }

                const cart = await api(

                    `${ROUTES.base}/${id}`,

                    'PATCH',

                    {
                        cantidad
                    }

                );

                render(cart);

                return;

            }

            /*
            |--------------------------------------------------------------
            | Eliminar producto
            |--------------------------------------------------------------
            */
            
            if (remove) {

                const id = remove.dataset.id;

                const cart = await api(

                    `${ROUTES.base}/${id}`,

                    'DELETE'

                );

                render(cart);

            }

        } catch (error) {

            console.error(error);

            showToast({

                title: 'Error',

                body: error.message

            });
              
        }

    });
    
        /*
    |--------------------------------------------------------------------------
    | Wishlist
    |--------------------------------------------------------------------------
    */

    document.addEventListener('click', async (e) => {

        const btn = e.target.closest('.btn-wishlist');

        if (!btn) {
            return;
        }

        e.preventDefault();

        try {

            const response = await api(

                '/wishlist/toggle',

                'POST',

                {
                    product_id: btn.dataset.id
                }

            );

            if (response.added) {

                btn.classList.remove(
                    'btn-outline-danger'
                );

                btn.classList.add(
                    'btn-danger'
                );

                btn.innerHTML = `
                    <i class="bi bi-heart-fill me-1"></i>
                    En favoritos
                `;

                showToast({

                    title: 'Favoritos',

                    body: 'Producto agregado a favoritos.'

                });

            } else {

                btn.classList.remove(
                    'btn-danger'
                );

                btn.classList.add(
                    'btn-outline-danger'
                );

                btn.innerHTML = `
                    <i class="bi bi-heart me-1"></i>
                    Favoritos
                `;

                showToast({

                    title: 'Favoritos',

                    body: 'Producto eliminado de favoritos.'

                });

            }

        } catch (error) {

            console.error(
                '[WISHLIST]',
                error
            );

            if (error.message.includes('401')) {

                window.location.href =
                    APP.routes.login;

                return;

            }

            showToast({

                title: 'Error',

                body: error.message

            });

        }

    });

});