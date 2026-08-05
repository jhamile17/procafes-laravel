@extends('layouts.app')

@section('title', 'Checkout | PROCÁFES')

@section('body-class', 'checkout-page')

@section('content')

<section class="checkout-section">

    <div class="container checkout-container">

        {{-- ==========================================================
            HEADER
        =========================================================== --}}

        <div class="customer-header">

            <h1 class="customer-title">
                Finalizar compra
            </h1>

            <p class="customer-subtitle">
                Revisa tu dirección, selecciona el método de pago y confirma tu pedido.
            </p>
        </div>

        {{-- ==========================================================
            FORMULARIO
        =========================================================== --}}

        <form
            id="checkoutForm"
            method="POST"
            action="{{ route('checkout.store') }}"
            data-billing-store="{{ route('customer.billing-profiles.store') }}"
            data-billing-search-ruc="{{ route('customer.billing-profiles.search-ruc') }}">

            @csrf

            <div class="row g-4 checkout-content">

                {{-- ==========================================================
                    INFORMACIÓN DEL PEDIDO
                =========================================================== --}}

                <div class="col-xl-8">

                    <div class="d-flex flex-column gap-4">

                        <x-checkout.delivery
                            :permiteEnvio="$permiteEnvio"
                            :address="$address" />
                        <x-checkout.payment
                            :paymentMethods="$paymentMethods" />
                        <x-checkout.billing
                            :billingProfiles="$billingProfiles" />

                    </div>

                </div>

                {{-- ==========================================================
                    RESUMEN
                =========================================================== --}}

                <div class="col-xl-4">

                    <div class="checkout-sidebar">

                        <x-checkout.summary
                            :cart="$cart"
                            :items="$items"
                            :cantidad="$cantidad"
                            :subtotal="$subtotal"
                            :igv="$igv"
                            :total="$total" />

                    </div>

                </div>

            </div>

        </form>

    </div>

</section>

@endsection