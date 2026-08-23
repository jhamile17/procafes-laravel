{{-- ==========================================================
    RECOJO EN TIENDA
========================================================== --}}

<div
    id="pickupPanel"
    class="checkout-pickup">

    {{-- Icono --}}
    <div class="checkout-pickup-icon">

        <i class="bi bi-shop"></i>

    </div>


    {{-- Contenido --}}
    <div class="checkout-pickup-content">

        <span class="checkout-pickup-badge">
            Recojo en tienda
        </span>


        <h4>
            PROCÁFES
        </h4>


        <p>
            Tu pedido estará listo para recoger
            en nuestro establecimiento.
        </p>


        <div class="checkout-pickup-meta">

            {{-- ==================================================
                UBICACIÓN
            =================================================== --}}

            <div class="checkout-pickup-item">

                <i class="bi bi-geo-alt-fill"></i>

                <span>
                    Pichanaki, Junín
                </span>

            </div>


            {{-- ==================================================
                HORARIO DINÁMICO
            =================================================== --}}

            <div class="checkout-pickup-item">

                <i class="bi bi-clock-fill"></i>

                <span>
                    Lunes a Domingo ·
                    {{ $horaApertura }}
                    -
                    {{ $horaCierre }}
                </span>

            </div>

        </div>

    </div>

</div>