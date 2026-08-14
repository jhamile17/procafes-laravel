import { currency } from './helpers';
import { MAX_QTY } from './config';

const isCartPage = document.body.classList.contains('cart-page');
const badge = document.getElementById('cartBadge');
const itemsBox = document.getElementById(
    isCartPage ? 'cartPageItems' : 'offcanvasCartItems'
);
const totalBox = document.getElementById(
    isCartPage ? 'cartPageTotal' : 'offcanvasCartTotal'
);
const clearBtn = document.querySelector(
    isCartPage ? '.cart-clear-btn' : '#cartOffcanvas .btn-clear-cart'
);

function renderBadge(count = 0) {
    const quantity = Number(count);

    if (badge) {
        badge.textContent = quantity;
        badge.classList.toggle('d-none', quantity <= 0);
    }

    const label = document.getElementById('cartCountLabel');

    if (label) {
        label.textContent = quantity === 1
            ? '1 producto'
            : `${quantity} productos`;
    }
}

function renderTotals(cart = {}) {
    if (!totalBox) {
        return;
    }

    totalBox.textContent = currency(Number(cart.total?? 0));
}

function renderEmpty() {
    if (!itemsBox) {
        return;
    }

    itemsBox.innerHTML = isCartPage
        ? `
            <div class="cart-empty">
                <i class="bi bi-cart-x"></i>
                <h3>Tu carrito esta vacio</h3>
                <p>Agrega tus bebidas favoritas para comenzar tu compra.</p>
            </div>
        `
        : `
            <div class="cart-empty-state">
                <div class="cart-empty-icon">
                    <i class="bi bi-cart-x"></i>
                </div>
                <h5>Tu carrito esta vacio</h5>
                <p>Agrega tus bebidas favoritas para comenzar tu compra.</p>
            </div>
        `;

    renderTotals({ total: 0 });

    if (clearBtn) {
        clearBtn.classList.add('d-none');
        clearBtn.disabled = true;
    }
}

function itemData(item) {
    const price = Number(item.unit_price ?? 0);
    const quantity = Number(item.quantity ?? 1);

    return {
        id: Number(item.product_id),
        name: item.name ?? item.product?.name ?? '',
        image: item.image ?? item.product?.image_url ?? '/images/no-image.png',
        price,
        quantity,
        subtotal: Number(item.subtotal ?? item.sub_total ?? (price * quantity)),
    };
}

function renderOffcanvasItem(item) {
    const { id, name, image, price, quantity, subtotal } = itemData(item);
    const card = document.createElement('div');

    card.className = 'offcanvas-cart-row';
    card.innerHTML = `
        <div class="offcanvas-item">
            <img src="${image}" alt="${name}" class="offcanvas-item-image">

            <div class="offcanvas-item-body">
                <div class="offcanvas-item-header">
                    <div class="offcanvas-item-info">
                        <div class="offcanvas-item-title">${name}</div>
                        <div class="offcanvas-item-price">${currency(price)} c/u</div>
                    </div>

                    <button
                        type="button"
                        class="offcanvas-remove btn-remove"
                        data-product-id="${id}"
                        aria-label="Eliminar">
                        <i class="bi bi-trash3"></i>
                    </button>
                </div>

                <div class="offcanvas-item-footer">
                    <div class="offcanvas-qty">
                        <button
                            type="button"
                            class="btn-dec"
                            data-product-id="${id}"
                            data-quantity="${quantity}"
                            ${quantity <= 1 ? 'disabled' : ''}
                            aria-label="Disminuir cantidad">
                            <i class="bi bi-dash"></i>
                        </button>

                        <span>${quantity}</span>

                        <button
                            type="button"
                            class="btn-inc"
                            data-product-id="${id}"
                            data-quantity="${quantity}"
                            ${quantity >= MAX_QTY ? 'disabled' : ''}
                            aria-label="Aumentar cantidad">
                            <i class="bi bi-plus"></i>
                        </button>
                    </div>

                    <div class="offcanvas-item-total">${currency(subtotal)}</div>
                </div>
            </div>
        </div>
    `;

    return card;
}

function renderCartPageItem(item) {
    const { id, name, image, price, quantity, subtotal } = itemData(item);
    const card = document.createElement('article');

    card.className = 'cart-item';
    card.innerHTML = `
        <div class="cart-item-image">
            <img src="${image}" alt="${name}">
        </div>

        <div class="cart-item-content">
            <div class="d-flex justify-content-between gap-3">
                <div>
                    <h3 class="cart-item-title">${name}</h3>
                    <div class="cart-item-price">${currency(price)} c/u</div>
                </div>

                <button
                    type="button"
                    class="btn btn-link text-danger p-0 btn-remove"
                    data-product-id="${id}"
                    aria-label="Eliminar ${name}">
                    <i class="bi bi-trash3"></i>
                </button>
            </div>

            <div class="cart-item-actions">
                <div class="quantity-control">
                    <button
                        type="button"
                        class="quantity-btn btn-dec"
                        data-product-id="${id}"
                        data-quantity="${quantity}"
                        ${quantity <= 1 ? 'disabled' : ''}
                        aria-label="Disminuir cantidad">
                        <i class="bi bi-dash"></i>
                    </button>

                    <span class="quantity-input d-flex align-items-center justify-content-center">${quantity}</span>

                    <button
                        type="button"
                        class="quantity-btn btn-inc"
                        data-product-id="${id}"
                        data-quantity="${quantity}"
                        ${quantity >= MAX_QTY ? 'disabled' : ''}
                        aria-label="Aumentar cantidad">
                        <i class="bi bi-plus"></i>
                    </button>
                </div>

                <div class="cart-item-subtotal">${currency(subtotal)}</div>
            </div>
        </div>
    `;

    return card;
}

function renderItem(item) {
    return isCartPage
        ? renderCartPageItem(item)
        : renderOffcanvasItem(item);
}

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

    if (isCartPage) {
        items.forEach(item => itemsBox.appendChild(renderItem(item)));
        return;
    }

    const wrapper = document.createElement('div');
    wrapper.className = 'offcanvas-cart-list';

    items.forEach(item => wrapper.appendChild(renderItem(item)));

    itemsBox.appendChild(wrapper);
}

function render(cart = {}) {
    renderBadge(Number(cart.count ?? 0));
    renderTotals(cart);

    const items = Array.isArray(cart.items)
        ? cart.items
        : Object.values(cart.items ?? {});

    renderItems(items);
    const checkoutBtn = document.getElementById('checkoutBtn');

    if (checkoutBtn) {
        const disabled = Number(cart.total ?? 0) <= 0;

        checkoutBtn.classList.toggle('disabled', disabled);
        checkoutBtn.style.pointerEvents = disabled ? 'none' : 'auto';
        checkoutBtn.style.opacity = disabled ? '0.6' : '1';

        if (disabled) {
            checkoutBtn.removeAttribute('href');
        } else {
            checkoutBtn.href = checkoutBtn.dataset.href;
        }
    }
}

function renderRecommendations(html) {
    if (!isCartPage) {
        return;
    }

    const container = document.getElementById('cart-recommendations');

    if (container) {
        container.innerHTML = html;
    }
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
