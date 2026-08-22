@extends('layouts.admin')

@section('title', 'Órdenes | PROCÁFES')

@section('content')

<div class="admin-list-page">

    <div class="admin-list-header">

        <div class="admin-list-heading">

            <div class="admin-list-heading-icon">

                <i class="bi bi-cart-check-fill"></i>

            </div>

            <div>

                <h1 class="admin-list-title">
                    Órdenes
                </h1>

                <p class="admin-list-subtitle">
                    Administra los pedidos realizados por los clientes.
                </p>

            </div>

        </div>

    </div>

    @if(session('success'))

        <div class="admin-list-message admin-list-message-success">

            <i class="bi bi-check-circle-fill"></i>

            <span>
                {{ session('success') }}
            </span>

        </div>

    @endif


    @if(session('info'))

        <div class="admin-list-message">

            <i class="bi bi-info-circle-fill"></i>

            <span>
                {{ session('info') }}
            </span>

        </div>

    @endif
    @if(session('error'))

    <div class="admin-list-message admin-list-message-error">

        <i class="bi bi-exclamation-triangle-fill"></i>

        <span>
            {{ session('error') }}
        </span>

    </div>

    @endif

    <div class="admin-list-card admin-order-filter-card">

        <div class="admin-order-filter-body">

            <form
                action="{{ route('admin.orders.index') }}"
                method="GET"
            >

                <div class="admin-order-filter-grid">

                    {{-- BUSCAR --}}

                    <div>

                        <label
                            for="order-search"
                            class="admin-order-filter-label"
                        >
                            Buscar
                        </label>

                        <div class="admin-order-search">

                            <i class="bi bi-search"></i>

                            <input
                                id="order-search"
                                type="text"
                                name="q"
                                value="{{ $q }}"
                                class="admin-order-search-input"
                                placeholder="Número de pedido, cliente o correo"
                            >

                        </div>

                    </div>


                    {{-- ESTADO --}}

                    <div>

                        <label
                            for="order-status"
                            class="admin-order-filter-label"
                        >
                            Estado
                        </label>

                        <select
                            id="order-status"
                            name="status"
                            class="admin-order-select"
                        >

                            <option value="">
                                Todos los estados
                            </option>

                            @foreach($estados as $estado)

                                <option
                                    value="{{ $estado->codigo }}"
                                    @selected($status === $estado->codigo)
                                >

                                    {{ $estado->nombre }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- BUSCAR --}}

                    <div>

                        <button
                            type="submit"
                            class="admin-order-search-button"
                        >

                            <i class="bi bi-search"></i>

                            Buscar

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- =====================================================
         TABLA
    ====================================================== --}}

    <div class="admin-list-card">

        <div class="admin-list-table-wrapper">

            <table class="admin-list-table admin-order-table">

                <thead>

                    <tr>

                        <th>
                            Pedido
                        </th>

                        <th>
                            Cliente
                        </th>

                        <th>
                            Estado
                        </th>

                        <th>
                            Total
                        </th>

                        <th>
                            Entrega
                        </th>

                        <th>
                            Fecha
                        </th>

                        <th class="admin-list-actions-column">
                            Acciones
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($orders as $order)

                        <tr>

                            {{-- PEDIDO --}}

                            <td>

                                <strong class="admin-order-number">
                                    {{ $order->numero_pedido }}
                                </strong>

                            </td>


                            {{-- CLIENTE --}}

                            <td>

                                <div class="admin-order-client">

                                    <strong>
                                        {{ $order->user?->name ?? 'Cliente' }}
                                    </strong>

                                    <small>
                                        {{ $order->user?->email }}
                                    </small>

                                </div>

                            </td>


                            {{-- ESTADO --}}

                            <td>

                                <form
                                    action="{{ route(
                                        'admin.orders.status',
                                        $order
                                    ) }}"
                                    method="POST"
                                    class="admin-order-status-form"
                                >

                                    @csrf

                                    @method('PATCH')

                                    <select
                                        name="estado_pedido_id"
                                        class="admin-order-status"
                                        onchange="this.form.submit()"
                                    >

                                        @foreach($estados as $estado)
                                              @php
                                                $esRecojo = in_array(
                                                    strtoupper((string) $order->delivery_type),
                                                    [
                                                        'PICKUP',
                                                        'RECOJO',
                                                        'RECOJO_EN_TIENDA',
                                                    ],
                                                    true
                                                );
                                                $estadosPermitidos = $esRecojo
                                                    ? [
                                                        'PENDIENTE',
                                                        'CONFIRMADO',
                                                        'PREPARACION',
                                                        'LISTO_RECOJO',
                                                        'ENTREGADO',
                                                        'CANCELADO',
                                                    ]
                                                    : [
                                                        'PENDIENTE',
                                                        'CONFIRMADO',
                                                        'PREPARACION',
                                                        'EN_CAMINO',
                                                        'ENTREGADO',
                                                        'CANCELADO',
                                                    ];
                                            @endphp
                                                @if(in_array($estado->codigo, $estadosPermitidos, true))

                                                <option
                                                    value="{{ $estado->id }}"
                                                    @selected(
                                                        $estado->id ===
                                                        $order->estado_pedido_id
                                                    )
                                                >

                                                    {{ $estado->nombre }}

                                                </option>

                                            @endif

                                        @endforeach

                                    </select>

                                </form>

                            </td>


                            {{-- TOTAL --}}

                            <td>

                                <strong class="admin-order-total">

                                    S/
                                    {{ number_format(
                                        $order->total_price,
                                        2
                                    ) }}

                                </strong>

                            </td>


                            {{-- TIPO DE ENTREGA --}}

                            <td>

                                <span class="admin-order-delivery">

                                    {{ $order->delivery_label }}

                                </span>

                            </td>


                            {{-- FECHA --}}

                            <td>

                                <span class="admin-order-date">

                                    {{ $order->created_at_formatted }}

                                </span>

                            </td>


                            {{-- ACCIONES --}}

                            <td class="admin-list-actions">

                                <div class="admin-actions">

                                    <a
                                        href="{{ route(
                                            'admin.orders.show',
                                            $order
                                        ) }}"
                                        class="admin-action admin-action-view"
                                        title="Ver pedido"
                                    >

                                        <i class="bi bi-eye-fill"></i>

                                    </a>
                                     {{-- Aprobar pago en tienda --}}

                                        @if(
                                            $order->payment &&
                                            $order->payment->paymentMethod?->codigo === 'store' &&
                                            $order->payment->estadoPago?->codigo === 'PENDING'
                                        )

                                            <form
                                                action="{{ route(
                                                    'admin.billing.approve-payment',
                                                    $order
                                                ) }}"
                                                method="POST"
                                                style="display:inline;"
                                            >

                                                @csrf

                                                @method('PATCH')

                                                <button
                                                    type="submit"
                                                    class="admin-action"
                                                    title="Aprobar pago"
                                                    onclick="return confirm(
                                                        '¿Confirmas que el cliente realizó el pago?'
                                                    )"
                                                >

                                                    <i class="bi bi-cash-coin"></i>

                                                </button>

                                            </form>

                                        @endif

                                    </div>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="7"
                                class="admin-list-empty"
                            >

                                <div class="admin-list-empty-icon">

                                    <i class="bi bi-inbox"></i>

                                </div>

                                <strong>
                                    No existen órdenes registradas
                                </strong>

                                <span>
                                    Los pedidos realizados por los clientes
                                    aparecerán aquí.
                                </span>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- =====================================================
             PAGINACIÓN
        ====================================================== --}}

        @if($orders->hasPages())

            <div class="admin-list-pagination">

                {{ $orders->onEachSide(1)->links('vendor.pagination.paginacion-admin') }}

            </div>

        @endif

    </div>

</div>

@endsection