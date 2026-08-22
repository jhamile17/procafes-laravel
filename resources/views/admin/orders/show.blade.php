@extends('layouts.admin')

@section('title', 'Pedido ' . $order->numero_pedido . ' | PROCÁFES')

@section('content')

<div class="admin-order-detail">

    {{-- =====================================================
         ENCABEZADO
    ====================================================== --}}

    <div class="admin-order-detail-header">

        <div class="admin-order-detail-heading">

            <div class="admin-order-detail-icon">
                <i class="bi bi-receipt-cutoff"></i>
            </div>

            <div>

                <h1 class="admin-order-detail-title">
                    Pedido {{ $order->numero_pedido }}
                </h1>

                <p class="admin-order-detail-subtitle">
                    Información completa del pedido.
                </p>

            </div>

        </div>

        <div style="display:flex; gap:10px; flex-wrap:wrap;">

            <a
                href="{{ route('admin.orders.download', $order) }}"
                class="admin-form-btn admin-form-btn-save"
                target="_blank"
            >
                <i class="bi bi-file-earmark-pdf-fill"></i>
                Descargar pedido
            </a>

            <a
                href="{{ route('admin.orders.index') }}"
                class="admin-form-btn admin-form-btn-save"
            >
                <i class="bi bi-arrow-left"></i>
                Volver a órdenes
            </a>

        </div>

    </div>


    {{-- =====================================================
         INFORMACIÓN
    ====================================================== --}}

    <div class="admin-order-detail-grid">


        {{-- =================================================
             CLIENTE Y PEDIDO
        ================================================== --}}

        <div class="admin-order-detail-sidebar">

            <section class="admin-order-detail-card">

                <div class="admin-order-detail-card-header">

                    <div class="admin-order-detail-section-icon">
                        <i class="bi bi-person-fill"></i>
                    </div>

                    <div>
                        <h2>Cliente</h2>

                        <span>
                            Información del comprador
                        </span>
                    </div>

                </div>


                <div class="admin-order-detail-card-body">

                    <div class="admin-order-detail-info">

                        <span>
                            Nombre
                        </span>

                        <strong>
                            {{ $order->user?->name ?? 'No registrado' }}
                        </strong>

                    </div>


                    <div class="admin-order-detail-info">

                        <span>
                            Correo electrónico
                        </span>

                        <strong>
                            {{ $order->user?->email ?? 'No registrado' }}
                        </strong>

                    </div>
                    <div class="admin-order-detail-info">
                    <span>
                        Teléfono
                    </span>

                    @if($order->user?->celular)

                        <a
                            href="https://wa.me/51{{ preg_replace('/\D/', '', $order->user->celular) }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            style="
                                color:#25D366;
                                text-decoration:none;
                                font-weight:600;
                            "
                        >
                            <i class="bi bi-whatsapp"></i>
                            {{ $order->user->celular }}
                        </a>

                    @else

                        <strong>
                            No registrado
                        </strong>

                    @endif

                </div>


                    <div class="admin-order-detail-info">

                        <span>
                            Estado
                        </span>

                        <strong class="admin-order-detail-status">
                            {{ $order->estadoPedido?->nombre ?? 'Sin estado' }}
                        </strong>

                    </div>


                    <div class="admin-order-detail-info">

                        <span>
                            Tipo de entrega
                        </span>

                        <strong>
                            {{ $order->delivery_label }}
                        </strong>

                    </div>


                    <div class="admin-order-detail-info">

                        <span>
                            Fecha
                        </span>

                        <strong>
                            {{ $order->created_at_formatted }}
                        </strong>

                    </div>

                </div>

            </section>


            {{-- =================================================
                 DIRECCIÓN
            ================================================== --}}

            <section class="admin-order-detail-card">

                <div class="admin-order-detail-card-header">

                    <div class="admin-order-detail-section-icon">
                        <i class="bi bi-geo-alt-fill"></i>
                    </div>

                    <div>
                        <h2>Dirección</h2>

                        <span>
                            Información de entrega
                        </span>
                    </div>

                </div>


                <div class="admin-order-detail-card-body">

                    @if(
                        $order->delivery_alias ||
                        $order->delivery_direccion ||
                        $order->delivery_distrito ||
                        $order->delivery_provincia ||
                        $order->delivery_departamento
                    )

                        <div class="admin-order-detail-address">

                            @if($order->delivery_alias)
                                <strong>
                                    {{ $order->delivery_alias }}
                                </strong>
                            @endif

                            @if($order->delivery_direccion)
                                <span>
                                    {{ $order->delivery_direccion }}
                                </span>
                            @endif

                            @if(
                                $order->delivery_distrito ||
                                $order->delivery_provincia
                            )

                                <span>

                                    {{ $order->delivery_distrito }}

                                    @if(
                                        $order->delivery_distrito &&
                                        $order->delivery_provincia
                                    )
                                        -
                                    @endif

                                    {{ $order->delivery_provincia }}

                                </span>

                            @endif

                            @if($order->delivery_departamento)

                                <span>
                                    {{ $order->delivery_departamento }}
                                </span>

                            @endif

                        </div>

                    @else

                        <div class="admin-order-detail-no-address">

                            <i class="bi bi-shop"></i>

                            <span>
                                Recojo en tienda
                            </span>

                        </div>

                    @endif


                    @if($order->delivery_referencia)

                        <div class="admin-order-detail-reference">

                            <span>
                                Referencia
                            </span>

                            <p>
                                {{ $order->delivery_referencia }}
                            </p>

                        </div>

                    @endif

                </div>

            </section>

        </div>


        {{-- =================================================
             PRODUCTOS
        ================================================== --}}

        <section class="admin-order-detail-card admin-order-detail-products">

            <div class="admin-order-detail-card-header">

                <div class="admin-order-detail-section-icon">
                    <i class="bi bi-bag-fill"></i>
                </div>

                <div>

                    <h2>
                        Productos
                    </h2>

                    <span>
                        Detalle de los productos del pedido
                    </span>

                </div>

            </div>


            <div class="admin-order-detail-table-wrapper">

                <table class="admin-order-detail-table">

                    <thead>

                        <tr>

                            <th>
                                Producto
                            </th>

                            <th class="text-center">
                                Cantidad
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

                        @forelse($items as $item)

                            <tr>

                                <td>

                                    <div class="admin-order-detail-product">

                                        <div class="admin-order-detail-product-icon">

                                            <i class="bi bi-cup-hot-fill"></i>

                                        </div>

                                        <strong>
                                            {{ $item->product?->name ?? 'Producto eliminado' }}
                                        </strong>

                                    </div>

                                </td>


                                <td class="text-center">

                                    <span class="admin-order-detail-quantity">
                                        {{ $item->quantity }}
                                    </span>

                                </td>


                                <td class="text-end">

                                    <span class="admin-order-detail-price">

                                        S/
                                        {{ number_format(
                                            $item->unit_price,
                                            2
                                        ) }}

                                    </span>

                                </td>


                                <td class="text-end">

                                    <strong class="admin-order-detail-subtotal">

                                        S/
                                        {{ number_format(
                                            $item->subtotal,
                                            2
                                        ) }}

                                    </strong>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="4"
                                    class="admin-order-detail-empty"
                                >

                                    <i class="bi bi-inbox"></i>

                                    <span>
                                        Este pedido no contiene productos.
                                    </span>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>


                    <tfoot>

                        <tr>

                            <td colspan="3">
                                Total del pedido
                            </td>

                            <td class="text-end">

                                <strong class="admin-order-detail-total">

                                    S/
                                    {{ number_format(
                                        $totals['order_total'],
                                        2
                                    ) }}

                                </strong>

                            </td>

                        </tr>

                    </tfoot>

                </table>

            </div>

        </section>

    </div>

</div>

@endsection