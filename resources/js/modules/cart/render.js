import { currency } from './helpers';
import { MAX_QTY } from './config';


const offcanvas = document.getElementById('cartOffcanvas');
const isOffcanvas = !!offcanvas;
const isCartPage = document.body.classList.contains('cart-page');
const badge = document.getElementById('cartBadge');
const itemsBox = document.getElementById('cartItems');
const totalBox = document.getElementById('cartTotal');
const clearBtn = document.getElementById('btnClearCart');


/*
|--------------------------------------------------------------------------
| Badge
|--------------------------------------------------------------------------
*/

function renderBadge(count = 0) {
    count = Number(count);
    if (badge) {
        badge.textContent = count;
        badge.classList.toggle(
            'd-none',
            count <= 0
        );
    }
    const label = document.getElementById('cartCountLabel');
    if (label){
        label.textContent = count === 1
         ? '1 producto'
         : `${count} productos`;
        }
    }


/*
|--------------------------------------------------------------------------
| Totales
|--------------------------------------------------------------------------
*/

function renderTotals(cart = {}) {
    if (!totalBox) {
        return;
    }
        totalBox.textContent = currency(
            Number(cart.subtotal ?? 0)
        );
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
         <div class="cart-empty-state">
            <div class="cart-empty-icon">
                <i class="bi bi-cart-x"></i>
            </div>
            <h5>
                Tu carrito está vacío
            </h5>
            <p>
                Agrega tus bebidas favoritas para comenzar tu compra.
            </p>
        </div>
    `;

    renderTotals({
        subtotal: 0
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
function renderItem(item, offcanvas = false) {

    const id = Number(item.product_id);

    const name =
        item.name ??
        item.product?.name ??
        '';

    const image =
        item.image ??
        item.product?.image_url ??
        '/images/no-image.png';

    const price = Number(item.unit_price ?? 0);

    const quantity = Number(item.quantity ?? 1);

    const subtotal = Number(
        item.subtotal ??
        item.sub_total ??
        (price * quantity)
    );

    /*
    |--------------------------------------------------------------------------
    | OFFCANVAS
    |--------------------------------------------------------------------------
    */

   if (offcanvas) {

    const card = document.createElement('div');

    card.className = 'offcanvas-cart-row';

    card.innerHTML = `

        <div class="offcanvas-item">

            <img
                src="${image}"
                alt="${name}"
                class="offcanvas-item-image">

            <div class="offcanvas-item-body">

                <div class="offcanvas-item-header">

                    <div class="offcanvas-item-info">

                        <div class="offcanvas-item-title">
                            ${name}
                        </div>

                        <div class="offcanvas-item-price">
                            ${currency(price)} c/u
                        </div>

                    </div>

                    <button
                        class="offcanvas-remove btn-remove"
                        data-product-id="${id}"
                        aria-label="Eliminar">

                        <i class="bi bi-trash3"></i>

                    </button>

                </div>

                <div class="offcanvas-item-footer">

                    <div class="offcanvas-qty">

                        <button
                            class="btn-dec"
                            data-product-id="${id}"
                            data-quantity="${quantity}"
                            ${quantity <= 1 ? 'disabled' : ''}>

                            <i class="bi bi-dash"></i>

                        </button>

                        <span>${quantity}</span>

                        <button
                            class="btn-inc"
                            data-product-id="${id}"
                            data-quantity="${quantity}"
                            ${quantity >= MAX_QTY ? 'disabled' : ''}>

                            <i class="bi bi-plus"></i>

                        </button>

                    </div>

                    <div class="offcanvas-item-total">
                        ${currency(subtotal)}
                    </div>

                </div>

            </div>

        </div>

    `;

    return card;
}
    /*
    |--------------------------------------------------------------------------
    | PÁGINA MI CARRITO
    |--------------------------------------------------------------------------
    */

    const card = document.createElement('div');

    card.className = 'cart-item-card';

    card.innerHTML = `

        <!-- AQUÍ VA EL HTML QUE YA TENÍA
             LA PÁGINA "MI CARRITO" -->

    `;

    return card;

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

    if (isOffcanvas) {

        const wrapper = document.createElement('div');
        wrapper.className = 'offcanvas-cart-list';

        items.forEach(item => {
            wrapper.appendChild(renderItem(item, true));
        });

        itemsBox.appendChild(wrapper);

    } else {

        items.forEach(item => {
            itemsBox.appendChild(renderItem(item, false));
        });

    }
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
    renderRecommendations,
};