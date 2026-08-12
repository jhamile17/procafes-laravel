<article class="customer-card order-detail">
    <div class="customer-card-body">
        <div class="order-card-header">
            <div>
                <h4 class="order-number">
                    Pedido #{{ $order->numero_pedido }}
                </h4>
                <span class="order-date">
                    {{ $order->created_at->format('d/m/Y') }}
                </span>

            </div>

            <span class="order-status">

                {{ $order->estadoPedido->nombre }}

            </span>

        </div>

        <hr class="my-4">

        <div class="order-items">

            @foreach($order->items as $item)

                <div class="order-item">

                    <div>

                        <strong>{{ $item->product->name }}</strong>

                        <div class="text-muted">

                            Cantidad: {{ $item->quantity }}

                        </div>

                    </div>

                    <div>

                        S/ {{ number_format($item->subtotal, 2) }}

                    </div>

                </div>

            @endforeach

        </div>

        <hr class="my-4">

        <div class="row g-4">

            <div class="col-md-6">

                <label class="customer-label">

                    Dirección de entrega

                </label>

                <div class="customer-value">

                    {{ $order->delivery_direccion }}

                </div>

            </div>

            <div class="col-md-6">

                <label class="customer-label">

                    Total pagado

                </label>

                <div class="customer-value">

                    S/ {{ number_format($order->total_price, 2) }}

                </div>

            </div>

        </div>

        @if($order->comprobante)

            <hr class="my-4">

            <div class="row g-4">

                <div class="col-md-6">

                    <label class="customer-label">

                        Comprobante

                    </label>

                    <div class="customer-value">

                        {{ $order->comprobante->tipo_comprobante }}

                        @if($order->comprobante->electronicDocument)

                            - {{ $order->comprobante->electronicDocument->numeroCompleto() }}

                        @endif

                    </div>

                </div>

                <div class="col-md-6">

                    <label class="customer-label">

                        Estado SUNAT

                    </label>

                    <div class="customer-value">

                        @if($order->comprobante->electronicDocument)

                            {{ $order->comprobante->electronicDocument->estado }}

                        @else

                            Pendiente

                        @endif

                    </div>

                </div>

            </div>

        @endif

        <div class="order-actions mt-4">

            <a
                href="{{ route('customer.orders') }}"
                class="customer-btn-secondary">

                <i class="bi bi-arrow-left me-2"></i>

                Volver

            </a>

            @if(
                $order->comprobante &&
                $order->comprobante->electronicDocument &&
                $order->comprobante->electronicDocument->pdf_url
            )

                <a
                    href="{{ $order->comprobante->electronicDocument->pdf_url }}"
                    target="_blank"
                    class="btn btn-primary">

                    <i class="bi bi-file-earmark-pdf me-2"></i>

                    Descargar {{ strtolower($order->comprobante->tipo_comprobante) }}

                </a>

            @endif

        </div>

    </div>

</article>