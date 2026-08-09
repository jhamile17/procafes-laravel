@extends('layouts.app')

@section('title', 'Pedido realizado | PROCÁFES')

@section('content')

<section class="checkout-success">

    <div class="container">

        <div class="success-card">

            <div class="success-icon">
                <i class="bi bi-check-circle-fill"></i>
            </div>

            <span class="success-badge">
                Pedido registrado
            </span>

            <h1 class="success-title">
                ¡Gracias por tu compra!
            </h1>

            <p class="success-text">
                Hemos recibido correctamente tu pedido.
            </p>

            <div class="order-summary">

                <div class="summary-item">
                    <span>Número de pedido</span>
                    <strong>{{ $order->numero_pedido }}</strong>
                </div>

                <div class="summary-item">
                    <span>Total</span>
                    <strong>S/ {{ number_format($order->total_price,2) }}</strong>
                </div>

                <div class="summary-item">
                    <span>Entrega</span>
                    <strong>
                        {{ $order->delivery_type == 'pickup'
                            ? 'Recojo en tienda'
                            : 'Delivery' }}
                    </strong>
                </div>

                <div class="summary-item">
                    <span>Estado</span>

                    <span class="status-pill">
                        {{ $order->estadoPedido->nombre }}
                    </span>

                </div>

            </div>

            <div class="success-actions">

                <a href="{{ route('customer.orders') }}"
                   class="btn btn-primary-custom">

                    Mis pedidos

                </a>

                <a href="{{ route('home') }}"
                   class="btn btn-outline-custom">

                    Volver al inicio

                </a>

            </div>

        </div>

    </div>

</section>

@endsection