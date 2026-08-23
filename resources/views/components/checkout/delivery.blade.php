@props([
    'permiteEnvio',
    'address',
    'horaApertura',
    'horaCierre',
])

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
            Indica cómo deseas recibir tu pedido.
        </p>

    </div>


    {{-- ==========================================================
        BODY
    =========================================================== --}}

    <div class="checkout-card-body">

        {{-- Opciones de entrega --}}
        @include('components.checkout.delivery.options')


        {{-- Panel Recojo --}}
        @include(
            'components.checkout.delivery.pickup',
            [
                'horaApertura' => $horaApertura,
                'horaCierre' => $horaCierre,
            ]
        )


        {{-- Panel Delivery --}}
        @include('components.checkout.delivery.shipping')


        {{-- Formulario Dirección --}}
        @include('components.checkout.delivery.address-form')

    </div>

</div>