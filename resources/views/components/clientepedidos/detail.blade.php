<article class="customer-card order-detail">

    <div class="customer-card-body">

        <div class="order-card-header">

            <div>

                <h4 class="order-number">

                    Pedido #{{ $order['numero'] }}

                </h4>

                <span class="order-date">

                    {{ $order['fecha'] }}

                </span>

            </div>

            <span class="order-status {{ $order['estado_class'] }}">

                {{ $order['estado'] }}

            </span>

        </div>

        <hr class="my-4">

        <div class="order-items">

            @foreach($order['items'] as $item)

                <div class="order-item">

                    <div>

                        <strong>{{ $item['nombre'] }}</strong>

                        <div class="text-muted">

                            Cantidad: {{ $item['cantidad'] }}

                        </div>

                    </div>

                    <div>

                        S/ {{ number_format($item['subtotal'], 2) }}

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

                    {{ $order['direccion'] }}

                </div>

            </div>

            <div class="col-md-6">

                <label class="customer-label">

                    Total pagado

                </label>

                <div class="customer-value">

                    S/ {{ number_format($order['total'], 2) }}

                </div>

            </div>

        </div>

        <div class="order-actions mt-4">

            <a
                href="{{ route('customer.orders') }}"
                class="btn btn-outline-primary">

                <i class="bi bi-arrow-left me-2"></i>

                Volver

            </a>

            @if(!empty($order['invoice_url']))

                <a
                    href="{{ $order['invoice_url'] }}"
                    target="_blank"
                    class="btn btn-primary">

                    <i class="bi bi-download me-2"></i>

                    Descargar comprobante

                </a>

            @endif

        </div>

    </div>

</article>