<div class="checkout-card">

    {{-- ==========================================================
        HEADER
    =========================================================== --}}

    <div class="checkout-card-header">

        <span class="checkout-card-badge">

            Paso 1

        </span>

        <h2 class="checkout-card-title">

            Método de entrega

        </h2>

        <p class="checkout-card-subtitle">

            Selecciona cómo deseas recibir tu pedido.

        </p>

    </div>

    {{-- ==========================================================
        BODY
    =========================================================== --}}

    <div class="checkout-card-body">

        @include('components.checkout.delivery.options')

        @unless($permiteEnvio)

            <div class="checkout-alert">

                <i class="bi bi-info-circle-fill"></i>

                <div>

                    <strong>

                        Este pedido solo puede recogerse en tienda.

                    </strong>

                    <p>

                        Contiene productos preparados.

                    </p>

                </div>

            </div>

        @endunless

        @include('components.checkout.delivery.pickup')

        @if($permiteEnvio)

            @include('components.checkout.delivery.shipping')

            @include('components.checkout.delivery.address-form')

        @endif

    </div>

</div>