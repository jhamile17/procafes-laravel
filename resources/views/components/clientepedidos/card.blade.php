@props([
    'order'
])

<article class="customer-card order-card">

    <div class="customer-card-body">

        <div class="order-card-header">

            <div>

                <h5 class="order-number">

                    Pedido #{{ $order['numero'] }}

                </h5>

                <span class="order-date">

                    {{ $order['fecha'] }}

                </span>

            </div>

            <span class="order-status {{ $order['estado_class'] }}">

                {{ $order['estado'] }}

            </span>

        </div>

        <div class="row mt-4 g-4">

            <div class="col-md-3">

                <label class="customer-label">

                    Productos

                </label>

                <div class="customer-value">

                    {{ $order['productos'] }} artículos

                </div>

            </div>

            <div class="col-md-3">

                <label class="customer-label">

                    Entrega

                </label>

                <div class="customer-value">

                    {{ ucfirst($order['delivery_type']) }}

                </div>

            </div>

            <div class="col-md-3">

                <label class="customer-label">

                    Total

                </label>

                <div class="customer-value">

                    S/ {{ $order['total'] }}

                </div>

            </div>

            <div class="col-md-3 d-flex align-items-end justify-content-md-end">

                <a
                    href="{{ route('customer.orders.show', $order['id']) }}"
                    class="btn btn-outline-primary">

                    <i class="bi bi-eye me-2"></i>

                    Ver detalle

                </a>

            </div>

        </div>

    </div>

</article>