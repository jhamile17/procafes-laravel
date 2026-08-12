<article class="customer-card order-detail">

    <div class="customer-card-body">

        {{-- Cabecera --}}
        <div class="order-detail-header">

            <div>

                <h3 class="order-number">
                    Pedido #{{ $order->numero_pedido }}
                </h3>

                <p>
                    Realizado el {{ $order->created_at->format('d/m/Y H:i') }}
                </p>

            </div>

            <span class="order-status {{ $order->estadoClass() }}">
                {{ $order->estadoPedido->nombre }}
            </span>

        </div>

        <hr class="my-3">

        {{-- Resumen --}}
        <div class="row g-3 order-summary">

            <div class="col-md-3">

                <div class="summary-card text-center">

                    <small>Método de pago</small>

                    <strong>
                        {{ $order->payment?->paymentMethod?->nombre ?? 'No registrado' }}
                    </strong>

                </div>

            </div>

            <div class="col-md-3">

                <div class="summary-card text-center">

                    <small>Tipo de entrega</small>

                    <strong>
                        {{ $order->delivery_type === 'pickup' ? 'Recojo en tienda' : 'Delivery' }}
                    </strong>

                </div>

            </div>

            <div class="col-md-3">

                <div class="summary-card text-center">

                    <small>Productos</small>

                    <strong>
                        {{ $order->totalItems() }}
                    </strong>

                </div>

            </div>

            <div class="col-md-3">

                <div class="summary-card text-center">

                    <small>Total</small>

                    <strong class="summary-total">
                        S/ {{ number_format($order->total_price,2) }}
                    </strong>

                </div>

            </div>

        </div>

        <hr class="my-3">

        {{-- Productos --}}
        <h5 class="order-section-title">

            Productos del pedido

        </h5>

        <div class="table-responsive">

            <table class="table align-middle">

                <thead>

                    <tr>

                        <th>Producto</th>

                        <th class="text-center">
                            Cant.
                        </th>

                        <th class="text-end">
                            Precio
                        </th>

                        <th class="text-end">
                            Subtotal
                        </th>

                    </tr>

                </thead>

                <tbody>

                    @foreach($order->items as $item)

                        <tr>

                            <td>

                                <strong>

                                    {{ $item->product->name }}

                                </strong>

                            </td>

                            <td class="text-center">

                                {{ $item->quantity }}

                            </td>

                            <td class="text-end">

                                S/ {{ number_format($item->unit_price,2) }}

                            </td>

                            <td class="text-end">

                                S/ {{ number_format($item->subtotal,2) }}

                            </td>

                        </tr>

                    @endforeach

                </tbody>

                <tfoot>

                    <tr>

                        <th colspan="3" class="text-end">

                            Total

                        </th>

                        <th class="text-end text-primary">

                            S/ {{ number_format($order->total_price,2) }}

                        </th>

                    </tr>

                </tfoot>

            </table>

        </div>

        <hr class="my-3">

        {{-- Información del pedido --}}
        <div class="row g-3">

            <div class="col-md-6">

                <div class="order-info-box">

                    <span class="order-info-title">

                        Dirección de entrega

                    </span>

                    <div class="order-info-value">

                        {{ $order->delivery_direccion }}

                    </div>

                </div>

            </div>

            <div class="col-md-6">

                <div class="order-info-box">

                    <span class="order-info-title">

                        Observaciones

                    </span>

                    <div class="order-info-value">

                        {{ $order->observaciones ?: 'Sin observaciones.' }}

                    </div>

                </div>

            </div>

        </div>

        @if($order->comprobante)

            <hr class="my-3">

            <h5 class="order-section-title">

                Comprobante electrónico

            </h5>

            <div class="order-document">

                <div class="row g-3">

                    <div class="col-md-6">

                        <span class="order-info-title">

                            Tipo de comprobante

                        </span>

                        <div class="order-info-value">

                            {{ ucfirst($order->comprobante->tipo_comprobante) }}

                        </div>

                        @if($order->comprobante->electronicDocument)

                            <div class="order-document-number mt-2">

                                {{ $order->comprobante->electronicDocument->numeroCompleto() }}

                            </div>

                        @endif

                    </div>

                    <div class="col-md-6">

                        <span class="order-info-title">

                            Estado SUNAT

                        </span>

                        <div class="order-info-value">

                            {{ $order->comprobante->electronicDocument->estado ?? 'Pendiente' }}

                        </div>

                    </div>

                </div>

            </div>

        @endif

        <hr class="my-3">

        {{-- Botones --}}
        <div class="order-actions">

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
                    class="customer-btn">

                    <i class="bi bi-file-earmark-pdf me-2"></i>

                    Descargar {{ ucfirst($order->comprobante->tipo_comprobante) }}

                </a>

            @endif

        </div>

    </div>

</article>