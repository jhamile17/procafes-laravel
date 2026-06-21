<div id="chatMessages"
     class="border rounded p-3 mb-3"
     style="height:350px;overflow-y:auto;">

    <div class="text-muted">
        Hola 👋 ¿En qué puedo ayudarte?
    </div>

</div>

<form id="chatForm">

    <?php echo csrf_field(); ?>

    <div class="input-group">

        <input
            type="text"
            id="message"
            class="form-control"
            placeholder="Escribe tu consulta...">

        <button
            class="btn btn-dark"
            type="submit">

            Enviar

        </button>

    </div>

</form>

<script>
document.addEventListener('DOMContentLoaded', () => {

    const form = document.getElementById('chatForm');

    if (!form) return;

    form.addEventListener('submit', async function(e) {

        e.preventDefault();

        const input = document.getElementById('message');
        const chat = document.getElementById('chatMessages');

        const text = input.value.trim();

        if (!text) return;

        chat.innerHTML += `
            <div class="text-end mb-2">
                <span class="badge text-bg-dark">${text}</span>
            </div>
        `;

        input.value = '';

        const response = await fetch('/chatbot/send', {
            method: 'POST',
            headers: {
                'Content-Type':'application/json',
                'X-CSRF-TOKEN':
                    document.querySelector(
                        'meta[name="csrf-token"]'
                    ).content
            },
            body: JSON.stringify({
                message:text
            })
        });

        const data = await response.json();

        chat.innerHTML += `
            <div class="mb-2">
                <div class="bg-light rounded p-2">
                    ${data.reply}
                </div>
            </div>
        `;

        chat.scrollTop = chat.scrollHeight;
    });

});
</script><?php /**PATH E:\Pagina-web-\resources\views/components/chat/window.blade.php ENDPATH**/ ?>