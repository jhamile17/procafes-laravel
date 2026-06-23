<div
    id="procafesChat"
    data-send-url="<?php echo e(url('/chatbot/send')); ?>"
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
        <?php echo csrf_field(); ?>

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

        // textContent evita que HTML o scripts sean interpretados.
        bubble.textContent = text;

        wrapper.appendChild(bubble);
        chat.appendChild(wrapper);
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
                const validationError = data.errors?.message?.[0];

                throw new Error(
                    validationError ||
                    data.message ||
                    'No se pudo procesar tu consulta.'
                );
            }

            if (!data.message) {
                throw new Error('El asistente no devolvió una respuesta válida.');
            }

            addMessage(data.message, 'bot');
        } catch (error) {
            showError(
                error.message ||
                'Ocurrió un error al comunicarse con el asistente.'
            );
        } finally {
            input.disabled = false;
            button.disabled = false;
            button.textContent = 'Enviar';
            input.focus();
        }
    });
});
</script><?php /**PATH E:\Pagina-web-\resources\views/components/chat/window.blade.php ENDPATH**/ ?>