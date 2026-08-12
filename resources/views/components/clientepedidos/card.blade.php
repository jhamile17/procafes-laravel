@props(['order'
'empresa'])

<div class="order-row">

    <div class="order-col">
        <strong>
            {{ $order->numero_pedido }}
        </strong>
    </div>

    <div class="order-col center">
        <span>
            {{ $order->created_at->format('d/m/Y') }}
        </span>
    </div>

    <div class="order-col center">
        <span class="order-status {{ $order->estadoClass() }}">
            {{ $order->estadoPedido->nombre }}
        </span>
    </div>

    <div class="order-col center">
        <span>
            {{ $order->totalItems() }}
        </span>
    </div>

    <div class="order-col center total">
        <strong>
            S/ {{ number_format($order->total_price, 2) }}
        </strong>
    </div>

    <div class="order-col action">

        <a
            href="{{ route('customer.orders.show', $order->id) }}"
            class="customer-btn btn btn-sm py-2"
        >
            <i class="bi bi-eye me-1"></i>
            Ver detalle
        </a>

    </div>

</div>