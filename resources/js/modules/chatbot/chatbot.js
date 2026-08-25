/*=========================================================
    INICIALIZAR CHATBOT
=========================================================*/

function initChatbot() {

    /*=====================================================
        ELEMENTOS PRINCIPALES
    =====================================================*/

    const root = document.getElementById("procafesChat");

    if (!root) {
        return;
    }

    /*
    |--------------------------------------------------------------------------
    | Evitar inicializar dos veces el mismo chatbot
    |--------------------------------------------------------------------------
    */

    if (root.dataset.chatbotInitialized === "true") {
        return;
    }

    root.dataset.chatbotInitialized = "true";


    /*=====================================================
        URL
    =====================================================*/

    const url = root.dataset.sendUrl;

    if (!url) {
        console.error("❌ No se encontró data-send-url");
        return;
    }


    /*=====================================================
        ELEMENTOS DEL CHAT
    =====================================================*/

    const form = document.getElementById("chatForm");
    const input = document.getElementById("chatMessage");
    const messages = document.getElementById("chatMessages");
    const typing = document.getElementById("typingIndicator");
    const button = document.getElementById("chatSendButton");

    if (!form || !input || !messages || !button) {

        console.error(
            "❌ Faltan elementos del chatbot:",
            {
                form,
                input,
                messages,
                button
            }
        );

        return;
    }


    /*=====================================================
        CSRF
    =====================================================*/

    const csrfElement =
        document.querySelector('meta[name="csrf-token"]');

    if (!csrfElement) {

        console.error(
            "❌ No se encontró el meta csrf-token"
        );

        return;
    }

    const csrf = csrfElement.content;


    /*=====================================================
        VENTANA CHATBOT
    =====================================================*/

    const chatbotWindow =
        document.getElementById("chatbotWindow");

    const chatbotToggle =
        document.getElementById("chatbotToggle");

    const chatbotClose =
        document.getElementById("chatbotClose");


    /*=====================================================
        ABRIR CHATBOT
    =====================================================*/

    chatbotToggle?.addEventListener("click", () => {

        if (!chatbotWindow) return;

        if (
            chatbotWindow.classList.contains("show")
        ) {

            chatbotWindow.classList.remove("show");

        } else {

            chatbotWindow.classList.add("show");

            setTimeout(() => {
                input.focus();
            }, 100);

        }

    });


    /*=====================================================
        CERRAR CHATBOT
    =====================================================*/

    chatbotClose?.addEventListener("click", () => {

        chatbotWindow?.classList.remove("show");

    });


    /*=====================================================
        SCROLL
    =====================================================*/

    function scrollBottom() {

        messages.scrollTop =
            messages.scrollHeight;

    }


    /*=====================================================
        MENSAJE USUARIO
    =====================================================*/

    function userMessage(text) {

        const safeText =
            escapeHtml(text);

        messages.insertAdjacentHTML(

            "beforeend",

            `
            <div class="user-message">

                <div class="bubble">

                    ${safeText}

                </div>

            </div>
            `

        );

        scrollBottom();
    }


    /*=====================================================
        MENSAJE BOT
    =====================================================*/

    function botMessage(text) {

        messages.insertAdjacentHTML(

            "beforeend",

            `
            <div class="bot-message">

                <div class="bubble">

                    ${formatMessage(text)}

                    <br><br>

                    <small class="text-muted">

                        💬 ¿Hay algo más en lo que pueda ayudarte?

                    </small>

                </div>

            </div>
            `

        );

        scrollBottom();
    }


    /*=====================================================
        FORMATEAR RESPUESTA DEL BOT
    =====================================================*/

    function formatMessage(text) {

        if (!text) {
            return "No encontré información.";
        }

        /*
        |--------------------------------------------------------------------------
        | Escapar HTML peligroso
        |--------------------------------------------------------------------------
        */

        let formatted =
            escapeHtml(String(text));


        /*
        |--------------------------------------------------------------------------
        | Saltos de línea
        |--------------------------------------------------------------------------
        */

        formatted =
            formatted.replace(/\n/g, "<br>");


        /*
        |--------------------------------------------------------------------------
        | Negritas
        |--------------------------------------------------------------------------
        */

        formatted =
            formatted.replace(
                /\*\*(.*?)\*\*/g,
                "<strong>$1</strong>"
            );


        /*
        |--------------------------------------------------------------------------
        | Links Markdown
        |--------------------------------------------------------------------------
        */

        formatted =
            formatted.replace(
                /\[([^\]]+)\]\((https?:\/\/[^\s)]+)\)/g,
                '<a href="$2" target="_blank" rel="noopener">$1</a>'
            );


        return formatted;
    }


    /*=====================================================
        ESCAPAR HTML
    =====================================================*/

    function escapeHtml(text) {

        const div =
            document.createElement("div");

        div.textContent = text;

        return div.innerHTML;
    }


    /*=====================================================
        ENVIAR MENSAJE
    =====================================================*/

    async function sendMessage(text) {

        if (!text || !text.trim()) {
            return;
        }

        userMessage(text);

        if (typing) {
            typing.classList.remove("d-none");
        }

        button.disabled = true;


        try {

            const response = await fetch(

                url,

                {
                    method: "POST",

                    headers: {

                        "Content-Type":
                            "application/json",

                        "Accept":
                            "application/json",

                        "X-CSRF-TOKEN":
                            csrf

                    },

                    body: JSON.stringify({

                        mensaje: text

                    })

                }

            );


            /*
            |--------------------------------------------------------------------------
            | Verificar respuesta HTTP
            |--------------------------------------------------------------------------
            */

            if (!response.ok) {

                throw new Error(
                    `Error HTTP ${response.status}`
                );

            }


            const data =
                await response.json();


            if (typing) {
                typing.classList.add("d-none");
            }


            /*
            |--------------------------------------------------------------------------
            | RESPUESTA DEL BOT
            |--------------------------------------------------------------------------
            */

            botMessage(

                data.message ??
                "No encontré información."

            );


            /*=================================================
                PRODUCTOS
            =================================================*/

            if (
                Array.isArray(data.products) &&
                data.products.length > 0
            ) {

                data.products.forEach(product => {

                    messages.insertAdjacentHTML(

                        "beforeend",

                        productCard(product)

                    );

                });


                /*
                |--------------------------------------------------------------------------
                | Eventos agregar al carrito
                |--------------------------------------------------------------------------
                */

                messages
                    .querySelectorAll(".add-cart")
                    .forEach(btn => {

                        btn.onclick = () => {

                            addToCart(

                                btn.dataset.product,

                                btn

                            );

                        };

                    });

            }


            scrollBottom();


        } catch (error) {

            console.error(
                "❌ Error chatbot:",
                error
            );


            if (typing) {
                typing.classList.add("d-none");
            }


            botMessage(

                "❌ No pude procesar tu consulta en este momento. Inténtalo nuevamente."

            );

        }


        button.disabled = false;

    }


    /*=====================================================
        TARJETA PRODUCTO
    =====================================================*/

    function productCard(product) {

        const image =
            product.image ||
            product.image_url ||
            "";


        const category =
            product.category ||
            "Producto";


        const description =
            product.description ||
            "";


        const available =
            product.available === true;


        const availabilityClass =
            available
                ? "text-success"
                : "text-danger";


        const availabilityText =
            available
                ? "Disponible"
                : "Agotado";


        const addButton =
            product.can_add_to_cart !== false &&
            available
                ? `
                    <button
                        class="btn w-100 mt-3 add-cart"
                        data-product="${product.id}"
                        type="button"
                    >
                        🛒 Agregar al carrito
                    </button>
                `
                : "";


        return `

        <div class="card chat-card">

            ${
                image
                    ? `
                        <img
                            src="${escapeHtml(image)}"
                            class="card-img-top"
                            alt="${escapeHtml(product.name || "")}"
                            loading="lazy"
                        >
                    `
                    : ""
            }


            <div class="card-body">


                <span class="badge mb-2">

                    ${escapeHtml(category)}

                </span>


                <h5 class="card-title">

                    ${escapeHtml(product.name || "")}

                </h5>


                ${
                    description
                        ? `
                            <p class="card-text small text-muted">

                                ${escapeHtml(description)}

                            </p>
                        `
                        : ""
                }


                <div
                    class="d-flex justify-content-between align-items-center"
                >

                    <strong>

                        ${escapeHtml(product.price || "")}

                    </strong>


                    <span class="${availabilityClass}">

                        ${availabilityText}

                    </span>

                </div>


                ${addButton}

            </div>

        </div>

        `;

    }


    /*=====================================================
        AGREGAR AL CARRITO
    =====================================================*/

    async function addToCart(productId, cartButton) {

        if (!cartButton) return;


        cartButton.disabled = true;

        cartButton.innerHTML =
            "Agregando...";


        try {

            const response = await fetch(

                "/cart",

                {

                    method: "POST",

                    headers: {

                        "Content-Type":
                            "application/json",

                        "Accept":
                            "application/json",

                        "X-CSRF-TOKEN":
                            csrf

                    },

                    body: JSON.stringify({

                        product_id:
                            productId,

                        cantidad: 1

                    })

                }

            );


            const data =
                await response.json();


            if (!response.ok) {

                throw new Error(

                    data.message ??
                    "No se pudo agregar el producto."

                );

            }


            botMessage(

                "✅ ¡Producto agregado correctamente al carrito!"

            );


            /*
            |--------------------------------------------------------------------------
            | Actualizar carrito
            |--------------------------------------------------------------------------
            */

            if (
                typeof window.refreshCart ===
                "function"
            ) {

                window.refreshCart();

            }


        } catch (error) {

            console.error(
                "❌ Error carrito:",
                error
            );


            botMessage(

                "❌ " +
                (
                    error.message ??
                    "No se pudo agregar el producto."
                )

            );

        }


        cartButton.disabled = false;

        cartButton.innerHTML =
            "🛒 Agregar al carrito";

    }


    /*=====================================================
        FORMULARIO
    =====================================================*/

    form.addEventListener(
        "submit",
        (event) => {

            event.preventDefault();


            const text =
                input.value.trim();


            if (!text) return;


            input.value = "";


            sendMessage(text);

        }
    );


    /*=====================================================
        BOTONES RÁPIDOS
    =====================================================*/

    root
        .querySelectorAll(".quick-btn")
        .forEach(btn => {

            btn.addEventListener(
                "click",
                () => {

                    const question =
                        btn.dataset.question;

                    if (!question) return;

                    sendMessage(question);

                }
            );

        });


    /*=====================================================
        ENTER
    =====================================================*/

    input.addEventListener(
        "keydown",
        (event) => {

            if (event.key === "Enter") {

                event.preventDefault();

                form.requestSubmit();

            }

        }
    );


    /*=====================================================
        ESC
    =====================================================*/

    /*
    | Importante:
    | Se utiliza una función propia para poder quitarla
    | cuando Livewire vuelva a cambiar la página.
    */

    const escapeHandler = (event) => {

        if (
            event.key === "Escape" &&
            chatbotWindow
        ) {

            chatbotWindow.classList.remove("show");

        }

    };


    document.addEventListener(
        "keydown",
        escapeHandler
    );


    /*
    |--------------------------------------------------------------------------
    | Guardar referencia para poder limpiar después
    |--------------------------------------------------------------------------
    */

    root._chatbotEscapeHandler =
        escapeHandler;

}


/*=========================================================
    INICIALIZACIÓN NORMAL
=========================================================*/

document.addEventListener(
    "DOMContentLoaded",
    initChatbot
);


/*=========================================================
    LIVEWIRE NAVIGATION
=========================================================*/

/*
|--------------------------------------------------------------------------
| Esto es lo importante para tu problema.
|
| Cuando pasas:
|
| Inicio → Productos
| Productos → Nosotros
| Nosotros → Ubícanos
|
| Livewire cambia el contenido sin volver a disparar
| DOMContentLoaded.
|--------------------------------------------------------------------------
*/

document.addEventListener(
    "livewire:navigated",
    () => {

        initChatbot();

    }
);


/*=========================================================
    TURBO / NAVEGACIÓN DINÁMICA
=========================================================*/

/*
|--------------------------------------------------------------------------
| Si alguna parte del proyecto utiliza Turbo,
| también soportamos su navegación.
|--------------------------------------------------------------------------
*/

document.addEventListener(
    "turbo:load",
    initChatbot
);