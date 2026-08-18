import { currency } from './helpers';

const MAX_CART_QUANTITY = 8;


/*=========================================================
    DOM
=========================================================*/

function getItemsBox() {

    return document.getElementById(
        'offcanvasCartItems'
    );

}


function getPageItemsBox() {

    return document.getElementById(
        'cartPageItems'
    );

}


function getTotalBox() {

    return document.getElementById(
        'offcanvasCartTotal'
    );

}


function getPageTotalBox() {

    return document.getElementById(
        'cartPageTotal'
    );

}


function getBadge() {

    return document.getElementById(
        'cartBadge'
    );

}


function getCountLabel() {

    return document.getElementById(
        'cartCountLabel'
    );

}


function getEmptyBox() {

    return document.getElementById(
        'cartEmpty'
    );

}


function getLoadingBox() {

    return document.getElementById(
        'cartLoading'
    );

}
 function getClearButton(){
    return document.querySelector('.btn-clear-cart');
 }

/*=========================================================
    LOADING
=========================================================*/

export function showLoading() {

    const loading =
        getLoadingBox();

    if (loading) {

        loading.classList.remove(
            'd-none'
        );

    }

}


export function hideLoading() {

    const loading =
        getLoadingBox();

    if (loading) {

        loading.classList.add(
            'd-none'
        );

    }

}


/*=========================================================
    BADGE
=========================================================*/

export function renderBadge(
    count = 0
) {

    const badge =
        getBadge();

    const label =
        getCountLabel();

    const quantity =
        Number(count) || 0;


    if (badge) {

        badge.textContent =
            quantity;

        badge.classList.toggle(
            'd-none',
            quantity <= 0
        );

    }


    if (label) {

        label.textContent =
            quantity === 1
                ? '1 producto'
                : `${quantity} productos`;

    }

}


/*=========================================================
    TOTAL
=========================================================*/

export function renderTotals(
    cart = {}
) {

    const total =
        Number(
            cart.total ?? 0
        );


    const totalBox =
        getTotalBox();


    if (totalBox) {

        totalBox.textContent =
            currency(total);

    }


    const pageTotal =
        getPageTotalBox();


    if (pageTotal) {

        pageTotal.textContent =
            currency(total);

    }

}


/*=========================================================
    ITEM OFFCANVAS
=========================================================*/
function createOffcanvasItem(item) {

    const element =
        document.createElement('div');

    element.className =
        'offcanvas-cart-row';


    const quantity =
        Number(item.quantity) || 1;


    const productId =
        Number(item.product_id);


    const unitPrice =
        Number(item.unit_price ?? 0);


    const subtotal =
        Number(item.subtotal ?? 0);


    element.innerHTML = `

        <div class="offcanvas-item">

            <img
                src="${item.image ?? ''}"
                alt="${item.name ?? 'Producto'}"
                class="offcanvas-item-image"
            >


            <div class="offcanvas-item-body">

                <div class="offcanvas-item-header">

                    <div class="offcanvas-item-info">

                        <div class="offcanvas-item-title">
                            ${item.name ?? 'Producto'}
                        </div>

                        <div class="offcanvas-item-price">
                            ${currency(unitPrice)}
                        </div>

                    </div>


                    <button
                        type="button"
                        class="offcanvas-remove btn-remove"
                        data-product-id="${productId}"
                        aria-label="Eliminar producto"
                        title="Eliminar producto"
                    >

                        <i class="bi bi-trash"></i>

                    </button>

                </div>


                <div class="offcanvas-item-footer">

                    <div class="offcanvas-qty">

                        <button
                            type="button"
                            class="btn-dec"
                            data-product-id="${productId}"
                            data-quantity="${quantity}"
                            aria-label="Disminuir cantidad"
                            ${quantity <= 1 ? 'disabled' : ''}
                        >

                            <i class="bi bi-dash"></i>

                        </button>


                        <span>
                            ${quantity}
                        </span>


                        <button
                            type="button"
                            class="btn-inc"
                            data-product-id="${productId}"
                            data-quantity="${quantity}"
                            aria-label="Aumentar cantidad"
                            ${quantity >= MAX_CART_QUANTITY ? 'disabled' : ''}
                        >

                            <i class="bi bi-plus"></i>

                        </button>

                    </div>


                    <div class="offcanvas-item-total">
                        ${currency(subtotal)}
                    </div>

                </div>


                <!-- ALERTA DEL PRODUCTO -->

                <div class="product-alert-container"></div>


            </div>

        </div>

    `;


    return element;
}
/*=========================================================
    ITEM PÁGINA CARRITO
=========================================================*/
function createCartPageItem(item) {

    const element =
        document.createElement('div');

    element.className =
        'cart-page-item';


    const quantity =
        Number(item.quantity) || 1;

    const productId =
        Number(item.product_id);

    const unitPrice =
        Number(item.unit_price ?? 0);

    const subtotal =
        Number(item.subtotal ?? 0);


    element.innerHTML = `

        <div class="cart-page-item-image">

            <img
                src="${item.image ?? ''}"
                alt="${item.name ?? 'Producto'}"
            >

        </div>


        <div class="cart-page-item-info">

            <h5>
                ${item.name ?? 'Producto'}
            </h5>


            <p>
                ${currency(unitPrice)}
            </p>


            <div class="cart-item-actions">
                 <div class="quantity-control">
                <button
                    type="button"
                    class="btn-dec"
                    data-product-id="${productId}"
                    data-quantity="${quantity}"
                    aria-label="Disminuir cantidad"
                    ${quantity <= 1 ? 'disabled' : ''}
                >

                    <i class="bi bi-dash"></i>

                </button>
                

                <span class="cart-item-quantity">
                    ${quantity}
                </span>


                <button
                    type="button"
                    class="btn-inc"
                    data-product-id="${productId}"
                    data-quantity="${quantity}"
                    aria-label="Aumentar cantidad"
                    ${quantity >= MAX_CART_QUANTITY ? 'disabled' : ''}
                >

                    <i class="bi bi-plus"></i>

                </button>


                <button
                    type="button"
                    class="btn-remove"
                    data-product-id="${productId}"
                    aria-label="Eliminar producto"
                >

                    <i class="bi bi-trash"></i>

                </button>

            </div>

        </div>
            <div class="product-alert-container"></div>


        </div>


        <div class="cart-page-item-subtotal">

            ${currency(subtotal)}

        </div>

    `;


    return element;
}


/*=========================================================
    CARRITO VACÍO
=========================================================*/

export function renderEmpty() {

    const itemsBox =
        getItemsBox();


    if (itemsBox) {

        itemsBox.innerHTML = '';

    }


    const pageItems =
        getPageItemsBox();


    if (pageItems) {

        pageItems.innerHTML = `

            <div class="cart-empty-state">

                <div class="cart-empty-icon">

                    <i class="bi bi-cart-x"></i>

                </div>


                <h5>

                    Tu carrito está vacío

                </h5>


                <p>

                    Agrega tus bebidas favoritas.

                </p>

            </div>

        `;

    }


    const empty =
        getEmptyBox();


    if (empty) {

        empty.classList.remove(
            'd-none'
        );

    }


    renderTotals({
        total: 0
    });

}


/*=========================================================
    ITEMS
=========================================================*/

export function renderItems(
    items = []
) {

    const offcanvas =
        getItemsBox();
    const page =
        getPageItemsBox();
    const clearButton = 
        getClearButton();

    if (offcanvas) {
        offcanvas.innerHTML = '';
    }
    if (page) {
        page.innerHTML = '';
    }


    if (!Array.isArray(items) || !items.length) {
        if (clearButton){
            clearButton.classList.add('d-none');
        }
        renderEmpty();
        return;
    }
    if (clearButton){
        clearButton.classList.remove('d-none');
    }

    const empty =
        getEmptyBox();


    if (empty) {

        empty.classList.add(
            'd-none'
        );

    }


    items.forEach(item => {

        if (offcanvas) {

            offcanvas.appendChild(
                createOffcanvasItem(item)
            );

        }


        if (page) {

            page.appendChild(
                createCartPageItem(item)
            );

        }

    });

}


/*=========================================================
    RENDER PRINCIPAL
=========================================================*/

export function render(
    cart = {}
) {

    const items =
        Array.isArray(cart.items)
            ? cart.items
            : [];


    renderBadge(
        cart.count ?? 0
    );


    renderItems(
        items
    );


    renderTotals(
        cart
    );


    hideLoading();

}


/*=========================================================
    RECOMENDACIONES
=========================================================*/

export function renderRecommendations(
    html
) {

    const container =
        document.getElementById(
            'cart-recommendations'
        );


    if (container) {

        container.innerHTML =
            html ?? '';

    }

}