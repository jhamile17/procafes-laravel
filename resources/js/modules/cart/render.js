// resources/js/modules/cart/render.js

import { currency } from './helpers';
import { MAX_QTY } from './config';

const badge = document.getElementById('cartBadge');
const itemsBox = document.getElementById('cartItems');

const subtotalBox = document.getElementById('cartSubtotal');
const igvBox = document.getElementById('cartIgv');
const totalBox = document.getElementById('cartTotal');

const clearBtn = document.getElementById('btnClearCart');

/*
|--------------------------------------------------------------------------
| Badge
|--------------------------------------------------------------------------
*/

function renderBadge(count = 0) {

    if (!badge) {
        return;
    }

    count = Number(count);

    badge.textContent = count;

    badge.classList.toggle(
        'd-none',
        count <= 0
    );

}

/*
|--------------------------------------------------------------------------
| Totales
|--------------------------------------------------------------------------
*/

function renderTotals(cart = {}) {

    if (subtotalBox) {
        subtotalBox.textContent = currency(
            Number(cart.subtotal ?? 0)
        );
    }

    if (igvBox) {
        igvBox.textContent = currency(
            Number(cart.igv ?? 0)
        );
    }

    if (totalBox) {
        totalBox.textContent = currency(
            Number(cart.total ?? 0)
        );
    }

}

/*
|--------------------------------------------------------------------------
| Carrito vacío
|--------------------------------------------------------------------------
*/

function renderEmpty() {

    if (!itemsBox) {
        return;
    }

    itemsBox.innerHTML = `
        <div class="text-center py-5 text-muted">

            <i class="bi bi-cart-x fs-1 d-block mb-3"></i>

            <h6 class="mb-2">
                Tu carrito está vacío
            </h6>

            <p class="mb-0 small">
                Agrega productos para comenzar tu compra.
            </p>

        </div>
    `;

    renderTotals({
        subtotal: 0,
        igv: 0,
        total: 0
    });

    if (clearBtn) {
        clearBtn.classList.add('d-none');
        clearBtn.disabled = true;
    }

}

/*
|--------------------------------------------------------------------------
| Item
|--------------------------------------------------------------------------
*/

function renderItem(item) {

    const id = Number(item.product_id);

    const name = item.name ?? item.product?.name ?? '';

    const image =
        item.image ??
        item.product?.image_url ??
        '/images/no-image.png';

    const price = Number(
        item.unit_price ?? 0
    );

    const quantity = Number(
        item.quantity ?? 1
    );

    const subtotal = Number(
        item.subtotal ??
        item.sub_total ??
        (price * quantity)
    );

    const div = document.createElement('div');

    div.className = 'list-group-item py-3';

    div.innerHTML = `
        <div class="d-flex gap-3">

            <img
                src="${image}"
                class="rounded border"
                width="70"
                height="70"
                style="object-fit:cover"
                alt="${name}"
            >

            <div class="flex-grow-1">

                <div class="fw-semibold mb-1">
                    ${name}
                </div>

                <small class="text-muted">
                    ${currency(price)}
                </small>

                <div class="d-flex justify-content-between align-items-center mt-3">

                    <div class="btn-group btn-group-sm">

                        <button
                            type="button"
                            class="btn btn-outline-secondary btn-dec"
                            data-product-id="${id}"
                            ${quantity <= 1 ? 'disabled' : ''}>
                            <i class="bi bi-dash"></i>
                        </button>

                        <button
                            class="btn btn-light"
                            disabled>
                            ${quantity}
                        </button>

                        <button
                            type="button"
                            class="btn btn-outline-secondary btn-inc"
                            data-product-id="${id}"
                            ${quantity >= MAX_QTY ? 'disabled' : ''}>
                            <i class="bi bi-plus"></i>
                        </button>

                    </div>

                    <strong>
                        ${currency(subtotal)}
                    </strong>

                    <button
                        type="button"
                        class="btn btn-sm btn-outline-danger btn-remove"
                        data-product-id="${id}">
                        <i class="bi bi-trash"></i>
                    </button>

                </div>

            </div>

        </div>
    `;

    return div;

}

/*
|--------------------------------------------------------------------------
| Lista de productos
|--------------------------------------------------------------------------
*/

function renderItems(items = []) {

    if (!itemsBox) {
        return;
    }

    itemsBox.innerHTML = '';

    if (!items.length) {
        renderEmpty();
        return;
    }

    if (clearBtn) {
        clearBtn.classList.remove('d-none');
        clearBtn.disabled = false;
    }

    items.forEach(item => {
        itemsBox.appendChild(
            renderItem(item)
        );
    });

}

/*
|--------------------------------------------------------------------------
| Render general
|--------------------------------------------------------------------------
*/

function render(cart = {}) {

    renderBadge(
        Number(cart.count ?? 0)
    );

    renderTotals(cart);

    const items = Array.isArray(cart.items)
        ? cart.items
        : Object.values(cart.items ?? {});

    renderItems(items);

}

/*
|--------------------------------------------------------------------------
| Recomendaciones
|--------------------------------------------------------------------------
*/

function renderRecommendations(html) {

    const container = document.getElementById(
        'cart-recommendations'
    );

    if (!container) {
        return;
    }

    container.innerHTML = html;

}

export {
    render,
    renderBadge,
    renderTotals,
    renderEmpty,
    renderItem,
    renderItems,
    renderRecommendations
};