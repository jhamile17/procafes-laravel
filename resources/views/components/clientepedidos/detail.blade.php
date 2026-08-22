<article class="customer-card order-detail">

    <div class="customer-card-body">

        {{-- =====================================================
             CABECERA
        ====================================================== --}}

        <div class="order-detail-header">

            <div>

                <h3 class="order-number">
                    Pedido #{{ $order->numero_pedido }}
                </h3>

                <p>
                    Realizado el
                    {{ $order->created_at->format('d/m/Y H:i') }}
                </p>

            </div>

        </div>
            {{-- =====================================================
                SEGUIMIENTO DEL PEDIDO
            ====================================================== --}}

            @php

                $esPickup = $order->delivery_type === 'pickup';

                $estadoActual = $order->estadoPedido?->codigo;

                if ($esPickup) {

                    $estadosSeguimiento = [
                        'CONFIRMADO' => 'Confirmado',
                        'PREPARACION' => 'En preparación',
                        'LISTO_RECOJO' => 'Listo para recoger',
                        'ENTREGADO' => 'Entregado',
                    ];

                } else {

                    $estadosSeguimiento = [
                        'CONFIRMADO' => 'Confirmado',
                        'PREPARACION' => 'En preparación',
                        'EN_CAMINO' => 'En camino',
                        'ENTREGADO' => 'Entregado',
                    ];

                }

                $codigos = array_keys($estadosSeguimiento);

                $indiceActual = array_search(
                    $estadoActual,
                    $codigos,
                    true
                );

                if ($indiceActual === false) {
                    $indiceActual = 0;
                }

            @endphp


            <div class="order-tracking">

                <div class="order-tracking-title">

                    <i class="bi bi-box-seam me-2"></i>

                    Seguimiento de tu pedido

                </div>


                <div class="tracking-list">

                    @foreach($estadosSeguimiento as $codigo => $nombre)

                        @php

                            $indice = array_search(
                                $codigo,
                                $codigos,
                                true
                            );

                            if ($indice < $indiceActual) {

                                $clase = 'completed';

                            } elseif ($indice === $indiceActual) {

                                $clase = 'current';

                            } else {

                                $clase = 'pending';

                            }

                        @endphp


                        <div class="tracking-step {{ $clase }}">

                            <div class="tracking-icon">

                                @if($codigo === 'CONFIRMADO')

                                    <i class="bi bi-cup-hot{{ $clase === 'current' ? '-fill' : '' }}"></i>

                                @elseif($codigo === 'PREPARACION')

                                    <i class="bi bi-fire"></i>

                                @elseif($codigo === 'LISTO_RECOJO')

                                    <i class="bi bi-cup-hot{{ $clase === 'current' ? '-fill' : '' }}"></i>

                                @elseif($codigo === 'EN_CAMINO')

                                    <i class="bi bi-bicycle"></i>

                                @elseif($codigo === 'ENTREGADO')

                                    <i class="bi bi-check-lg"></i>

                                @else

                                    <i class="bi bi-circle"></i>

                                @endif

                            </div>


                            <div class="tracking-content">

                                <strong>
                                    {{ $nombre }}
                                </strong>

                                @if($clase === 'current')

                                    <span>
                                        Estado actual
                                    </span>

                                @endif

                            </div>

                        </div>

                    @endforeach

                </div>


                {{-- =====================================================
                    MENSAJE CONTEXTUAL
                ====================================================== --}}

                <div class="order-tracking-message">

                    @if($estadoActual === 'CONFIRMADO')

                        <strong>
                            ¡Tu pedido fue confirmado!
                        </strong>

                        <span>
                            Pronto comenzaremos a prepararlo.
                        </span>


                    @elseif($estadoActual === 'PREPARACION')

                        <strong>
                            Estamos preparando tu pedido.
                        </strong>

                        <span>
                            Te avisaremos cuando esté listo.
                        </span>


                    @elseif($estadoActual === 'LISTO_RECOJO')

                        <strong>
                            ¡Tu pedido está listo para recoger!
                        </strong>

                        <span>
                            Puedes acercarte a la tienda para recogerlo.
                        </span>


                    @elseif($estadoActual === 'EN_CAMINO')

                        <strong>
                            ¡Tu pedido está en camino!
                        </strong>

                        <span>
                            Pronto llegará a la dirección indicada.
                        </span>


                    @elseif($estadoActual === 'ENTREGADO')

                        <strong>
                            ¡Pedido entregado!
                        </strong>

                        <span>
                            Gracias por comprar en PROCÁFES.
                        </span>


                    @elseif($estadoActual === 'CANCELADO')

                        <strong>
                            Pedido cancelado
                        </strong>

                        <span>
                            Este pedido ya no se encuentra activo.
                        </span>


                    @else

                        <strong>
                            Pedido pendiente de confirmación
                        </strong>

                        <span>
                            Estamos esperando la confirmación de tu pedido.
                        </span>

                    @endif

                </div>

            </div>


            <hr class="my-3">


        {{-- =====================================================
             RESUMEN
        ====================================================== --}}

        <div class="row g-3 order-summary">

            <div class="col-md-3">

                <div class="summary-card text-center">

                    <small>
                        Método de pago
                    </small>

                    <strong>
                        {{ $order->payment?->paymentMethod?->nombre ?? 'No registrado' }}
                    </strong>

                </div>

            </div>


            <div class="col-md-3">

                <div class="summary-card text-center">

                    <small>
                        Tipo de entrega
                    </small>

                    <strong>

                        {{ $esPickup
                            ? 'Recojo en tienda'
                            : 'Delivery'
                        }}

                    </strong>

                </div>

            </div>


            <div class="col-md-3">

                <div class="summary-card text-center">

                    <small>
                        Productos
                    </small>

                    <strong>
                        {{ $order->totalItems() }}
                    </strong>

                </div>

            </div>


            <div class="col-md-3">

                <div class="summary-card text-center">

                    <small>
                        Total
                    </small>

                    <strong class="summary-total">

                        S/
                        {{ number_format($order->total_price, 2) }}

                    </strong>

                </div>

            </div>

        </div>


        <hr class="my-3">


        {{-- =====================================================
             PRODUCTOS
        ====================================================== --}}

        <h5 class="order-section-title">

            Productos del pedido

        </h5>


        <div class="table-responsive">

            <table class="table align-middle">

                <thead>

                    <tr>

                        <th>
                            Producto
                        </th>

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

                                S/
                                {{ number_format($item->unit_price, 2) }}

                            </td>


                            <td class="text-end">

                                S/
                                {{ number_format($item->subtotal, 2) }}

                            </td>

                        </tr>

                    @endforeach

                </tbody>


                <tfoot>

                    <tr>

                        <th
                            colspan="3"
                            class="text-end"
                        >
                            Total
                        </th>

                        <th
                            class="text-end text-primary"
                        >

                            S/
                            {{ number_format($order->total_price, 2) }}

                        </th>

                    </tr>

                </tfoot>

            </table>

        </div>


        <hr class="my-3">


        {{-- =====================================================
             INFORMACIÓN DE ENTREGA
        ====================================================== --}}

        <div class="row g-3">


            <div class="col-md-6">

                <div class="order-info-box">

                    <span class="order-info-title">

                        {{ $esPickup
                            ? 'Lugar de recojo'
                            : 'Dirección de entrega'
                        }}

                    </span>


                    <div class="order-info-value">

                        @if($esPickup)

                            {{ $empresa->direccion }}

                        @else

                            {{ $order->delivery_direccion }}

                            @if($order->delivery_numero)

                                N.º {{ $order->delivery_numero }}

                            @endif

                            @if($order->delivery_distrito)

                                <br>

                                {{ $order->delivery_distrito }}

                            @endif

                        @endif

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


        {{-- =====================================================
             COMPROBANTE
        ====================================================== --}}

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

                            {{ ucfirst(
                                $order->comprobante->tipo_comprobante
                            ) }}

                        </div>


                        @if($order->comprobante->electronicDocument)

                            <div class="order-document-number mt-2">

                                {{
                                    $order
                                        ->comprobante
                                        ->electronicDocument
                                        ->numeroCompleto()
                                }}

                            </div>

                        @endif

                    </div>


                    <div class="col-md-6">

                        <span class="order-info-title">
                            Estado SUNAT
                        </span>


                        <div class="order-info-value">

                            {{
                                $order
                                    ->comprobante
                                    ->electronicDocument
                                    ->estado
                                    ?? 'Pendiente'
                            }}

                        </div>

                    </div>

                </div>

            </div>

        @endif


        <hr class="my-3">


        {{-- =====================================================
             BOTONES
        ====================================================== --}}

        <div class="order-actions">

            <a
                href="{{ route('customer.orders') }}"
                class="customer-btn-secondary"
            >

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
                    class="customer-btn btn-sm py-2"
                >

                    <i class="bi bi-file-earmark-pdf me-2"></i>

                    Descargar
                    {{ ucfirst(
                        $order->comprobante->tipo_comprobante
                    ) }}

                </a>

            @endif

        </div>

    </div>

</article>