<div
    id="procafesChat"
    data-send-url="{{ url('/chatbot/send') }}"
>
    <div
        id="chatMessages"
        class="border rounded p-3 mb-3 bg-white"
        style="height: 350px; overflow-y: auto;"
        aria-live="polite"
    >
        <div class="mb-2">
            <div class="bg-light rounded p-2">
                Hola, soy el asistente de PROCAFES. Puedo ayudarte con productos,
                café, pedidos, envíos, pagos, horarios y ayuda básica.
            </div>
        </div>
    </div>

    <form id="chatForm" novalidate>
        @csrf

        <div class="input-group">
            <input
                type="text"
                id="chatMessage"
                class="form-control"
                placeholder="Escribe tu consulta..."
                maxlength="500"
                autocomplete="off"
                aria-label="Mensaje para el asistente"
            >

            <button
                id="chatSendButton"
                class="btn btn-dark"
                type="submit"
            >
                Enviar
            </button>
        </div>

        <div
            id="chatError"
            class="text-danger small mt-2 d-none"
            role="alert"
        ></div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const chatRoot = document.getElementById('procafesChat');

    if (!chatRoot || chatRoot.dataset.initialized === 'true') {
        return;
    }

    chatRoot.dataset.initialized = 'true';

    const form = document.getElementById('chatForm');
    const input = document.getElementById('chatMessage');
    const chat = document.getElementById('chatMessages');
    const button = document.getElementById('chatSendButton');
    const errorBox = document.getElementById('chatError');
    const sendUrl = chatRoot.dataset.sendUrl;
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

    function addMessage(text, type) {
        const wrapper = document.createElement('div');
        wrapper.className = type === 'user'
            ? 'text-end mb-2'
            : 'mb-2';

        const bubble = document.createElement('div');
        bubble.className = type === 'user'
            ? 'd-inline-block bg-dark text-white rounded p-2'
            : 'd-inline-block bg-light rounded p-2';

        bubble.textContent = text;

        wrapper.appendChild(bubble);
        chat.appendChild(wrapper);
        chat.scrollTop = chat.scrollHeight;
    }

    function refreshCart() {
        if (typeof window.refreshCart === 'function') {
            window.refreshCart();
        }
    }

    function openCart() {
        const offcanvasElement = document.getElementById('cartOffcanvas');

        if (!offcanvasElement || typeof bootstrap === 'undefined') {
            addMessage(
                'No pude abrir el carrito. Puedes continuar desde el botón de carrito de la página.',
                'bot'
            );
            return;
        }

        refreshCart();

        bootstrap.Offcanvas
            .getOrCreateInstance(offcanvasElement)
            .show();
    }

    function addProducts(products) {
        if (!Array.isArray(products) || products.length === 0) {
            return;
        }

        products.forEach((product) => {
            const card = document.createElement('div');
            card.className = 'card mb-2 shadow-sm';

            const body = document.createElement('div');
            body.className = 'card-body p-2';

            if (product.image_url) {
                const image = document.createElement('img');
                image.src = product.image_url;
                image.alt = product.name;
                image.className = 'img-fluid rounded mb-2';
                image.style.maxHeight = '120px';
                image.style.objectFit = 'cover';
                image.style.width = '100%';
                body.appendChild(image);
            }

            const name = document.createElement('h3');
            name.className = 'h6 mb-1';
            name.textContent = product.name;
            body.appendChild(name);

            const description = document.createElement('p');
            description.className = 'small text-muted mb-1';
            description.textContent = product.description || 'Producto PROCAFES';
            body.appendChild(description);

            const price = document.createElement('p');
            price.className = 'fw-bold mb-2';
            price.textContent = product.price;
            body.appendChild(price);

            const actions = document.createElement('div');
            actions.className = 'd-flex gap-2 flex-wrap';

            const addButton = document.createElement('button');
            addButton.type = 'button';
            addButton.className = 'btn btn-dark btn-sm';
            addButton.textContent = 'Agregar al carrito';

            addButton.addEventListener('click', async () => {
                addButton.disabled = true;
                addButton.textContent = 'Agregando...';

                try {
                    const response = await fetch('/cart/add', {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        body: JSON.stringify({
                            product_id: product.id,
                            quantity: 1,
                        }),
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        throw new Error(
                            data.message || 'No se pudo agregar el producto.'
                        );
                    }

                    refreshCart();

                    addMessage(
                        `${product.name} fue agregado al carrito.`,
                        'bot'
                    );

                    addButton.textContent = 'Agregado';
                } catch (error) {
                    addMessage(
                        error.message ||
                        'No se pudo agregar el producto al carrito.',
                        'bot'
                    );

                    addButton.disabled = false;
                    addButton.textContent = 'Agregar al carrito';
                }
            });

            const viewCartButton = document.createElement('button');
            viewCartButton.type = 'button';
            viewCartButton.className = 'btn btn-outline-secondary btn-sm';
            viewCartButton.textContent = 'Ver carrito';
            viewCartButton.addEventListener('click', openCart);

            const checkoutLink = document.createElement('a');
            checkoutLink.href = '/checkout';
            checkoutLink.className = 'btn btn-success btn-sm';
            checkoutLink.textContent = 'Finalizar compra';

            actions.appendChild(addButton);
            actions.appendChild(viewCartButton);
            actions.appendChild(checkoutLink);

            body.appendChild(actions);
            card.appendChild(body);
            chat.appendChild(card);
        });

        chat.scrollTop = chat.scrollHeight;
    }

    function showError(message) {
        errorBox.textContent = message;
        errorBox.classList.remove('d-none');
    }

    function hideError() {
        errorBox.textContent = '';
        errorBox.classList.add('d-none');
    }

    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        hideError();

        const message = input.value.trim();

        if (message.length < 2) {
            showError('Escribe una consulta de al menos 2 caracteres.');
            return;
        }

        if (!csrfToken) {
            showError('No se encontró el token de seguridad. Recarga la página.');
            return;
        }

        addMessage(message, 'user');

        input.value = '';
        input.disabled = true;
        button.disabled = true;
        button.textContent = 'Enviando...';

        try {
            const response = await fetch(sendUrl, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({
                    message: message,
                }),
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(
                    data.errors?.message?.[0] ||
                    data.message ||
                    'No se pudo procesar tu consulta.'
                );
            }

            if (!data.message) {
                throw new Error(
                    'El asistente no devolvió una respuesta válida.'
                );
            }

            addMessage(data.message, 'bot');
            addProducts(data.products);
        } catch (error) {
            addMessage(
                error.message ||
                'El asistente está temporalmente no disponible. Intenta nuevamente en unos segundos.',
                'bot'
            );
        } finally {
            input.disabled = false;
            button.disabled = false;
            button.textContent = 'Enviar';
            input.focus();
        }
    });
});
</script>