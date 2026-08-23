@extends('layouts.app')

@section('title', 'Finalizar Compra | PROCÁFES')

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
                Revisa tu dirección, selecciona el método de pago y
                confirma tu pedido.
            </p>

        </div>


        {{-- ==========================================================
            MENSAJE DE HORARIO - TICKER
        =========================================================== --}}

        @if(!$horarioDisponible)

            <div class="checkout-ticker">

                <div class="checkout-ticker-track">

                    <span>
                        ☕ PROCÁFES · En este momento estamos fuera de
                        nuestro horario de atención.
                    </span>

                    <span>
                        🕐 Nuestro horario es de
                        {{ $horaApertura }} a {{ $horaCierre }}.
                    </span>

                    <span>
                        ☕ PROCÁFES · En este momento estamos fuera de
                        nuestro horario de atención.
                    </span>

                    <span>
                        🕐 Nuestro horario es de
                        {{ $horaApertura }} a {{ $horaCierre }}.
                    </span>

                </div>

            </div>

        @endif


        {{-- ==========================================================
            FORMULARIO
        =========================================================== --}}

        <form
            id="checkoutForm"
            method="POST"
            action="{{ route('checkout.store') }}"
        >

            @csrf


            {{-- ==========================================================
                INDICADOR DE PASOS
            =========================================================== --}}

            <div class="checkout-steps mb-4">

                <div
                    id="stepIndicator1"
                    class="checkout-step-indicator is-active"
                >
                    <span class="checkout-step-number">
                        1
                    </span>

                    <span class="checkout-step-label">
                        Entrega
                    </span>
                </div>


                <div class="checkout-step-line"></div>


                <div
                    id="stepIndicator2"
                    class="checkout-step-indicator"
                >
                    <span class="checkout-step-number">
                        2
                    </span>

                    <span class="checkout-step-label">
                        Pago
                    </span>
                </div>


                <div class="checkout-step-line"></div>


                <div
                    id="stepIndicator3"
                    class="checkout-step-indicator"
                >
                    <span class="checkout-step-number">
                        3
                    </span>

                    <span class="checkout-step-label">
                        Comprobante
                    </span>
                </div>

            </div>


            {{-- ==========================================================
                CONTENIDO
            =========================================================== --}}

            <div class="row g-4 checkout-content">


                {{-- ======================================================
                    INFORMACIÓN DEL PEDIDO
                ======================================================= --}}

                <div class="col-xl-8">


                    {{-- ==================================================
                        PASO 1 - ENTREGA
                    =================================================== --}}

                    <div id="checkoutStep1">

                        <x-checkout.delivery
                            :permiteEnvio="$permiteEnvio"
                            :address="$address"
                            :horaApertura="$horaApertura"
                            :horaCierre="$horaCierre"
                        />


                        <div class="checkout-step-actions">

                            <button
                                type="button"
                                id="checkoutNext1"
                                class="customer-btn"
                                @disabled(!$horarioDisponible)
                            >
                                Continuar

                                <i class="bi bi-arrow-right"></i>
                            </button>

                        </div>

                    </div>


                    {{-- ==================================================
                        PASO 2 - PAGO
                    =================================================== --}}

                    <div
                        id="checkoutStep2"
                        class="d-none"
                    >

                        <x-checkout.payment />


                        <div class="checkout-step-actions">

                            <button
                                type="button"
                                id="checkoutBack2"
                                class="customer-btn-secondary"
                            >
                                <i class="bi bi-arrow-left"></i>

                                Atrás
                            </button>


                            <button
                                type="button"
                                id="checkoutNext2"
                                class="customer-btn"
                            >
                                Continuar

                                <i class="bi bi-arrow-right"></i>
                            </button>

                        </div>

                    </div>


                    {{-- ==================================================
                        PASO 3 - COMPROBANTE
                    =================================================== --}}

                    <div
                        id="checkoutStep3"
                        class="d-none"
                    >

                        <x-checkout.invoice />


                        <div class="checkout-step-actions">

                            <button
                                type="button"
                                id="checkoutBack3"
                                class="customer-btn-secondary"
                            >
                                <i class="bi bi-arrow-left"></i>

                                Atrás
                            </button>


                            <button
                                type="submit"
                                class="customer-btn"
                                @disabled(!$horarioDisponible)
                            >
                                Confirmar pedido

                                <i class="bi bi-check-circle"></i>
                            </button>

                        </div>

                    </div>

                </div>


                {{-- ======================================================
                    RESUMEN
                ======================================================= --}}

                <div class="col-xl-4">

                    <div class="checkout-sidebar">

                        <x-checkout.summary
                            :cart="$cart"
                            :items="$items"
                            :cantidad="$cantidad"
                            :subtotal="$subtotal"
                            :igv="$igv"
                            :total="$total"
                        />

                    </div>

                </div>

            </div>

        </form>

    </div>

</section>

@endsection