<button
    type="button"
    class="btn btn-dark rounded-circle shadow position-fixed"
    style="width: 60px; height: 60px; bottom: 100px; right: 24px; z-index: 1050;"
    data-bs-toggle="modal"
    data-bs-target="#chatbotModal"
    aria-label="Abrir asistente PROCAFES"
>
    <i class="bi bi-robot fs-4"></i>
</button>

<div
    class="modal fade"
    id="chatbotModal"
    tabindex="-1"
    aria-labelledby="chatbotModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title fs-5" id="chatbotModalLabel">
                    Asistente PROCAFES
                </h2>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Cerrar"
                ></button>
            </div>

            <div class="modal-body">
                <x-chat.window />
            </div>
        </div>
    </div>
</div>