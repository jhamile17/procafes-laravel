@extends('layouts.app')

@section('title', 'Checkout | PROCÁFES')

@section('body-class', 'checkout-page')

@section('content')

<section class="checkout-section">

    <div class="container">

        <div class="checkout-header">

            <div>

                <span class="checkout-badge">
                    Finalizar compra
                </span>

                <h1 class="checkout-title">
                    Confirma tu pedido
                </h1>

                <p class="checkout-subtitle">
                    Verifica tu dirección de envío, selecciona el método de pago y revisa el resumen antes de confirmar tu compra.
                </p>

            </div>

        </div>

        <form
            id="checkoutForm"
            method="POST"
            action="{{ route('checkout.store') }}">

            @csrf

            <div class="row g-4">

                {{-- Columna izquierda --}}
                <div class="col-lg-8">

                    <x-checkout.address
                        :address="$address"
                    />

                    <x-checkout.payment
                        :paymentMethods="$paymentMethods"
                    />

                </div>

                {{-- Columna derecha --}}
                <div class="col-lg-4">

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

        </form>

    </div>

</section>

@endsection

