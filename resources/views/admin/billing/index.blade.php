@extends('layouts.admin')

@section('title', 'Facturación | PROCÁFES')

@section('content')

<div class="admin-billing-page">

    {{-- =====================================================
         ENCABEZADO
    ====================================================== --}}

    <div class="admin-billing-header">

        <div class="admin-billing-heading">

            <div class="admin-billing-heading-icon">
                <i class="bi bi-receipt-cutoff"></i>
            </div>

            <div>

                <h1 class="admin-billing-title">
                    Facturación electrónica
                </h1>

                <p class="admin-billing-subtitle">
                    Consulta y gestiona los comprobantes electrónicos de PROCÁFES.
                </p>

            </div>

        </div>

    </div>


    {{-- =====================================================
         MENSAJES
    ====================================================== --}}

    @if(session('success'))

        <div class="admin-billing-message admin-billing-message-success">

            <i class="bi bi-check-circle-fill"></i>

            <span>
                {{ session('success') }}
            </span>

        </div>

    @endif


    @if(session('error'))

        <div class="admin-billing-message admin-billing-message-error">

            <i class="bi bi-exclamation-triangle-fill"></i>

            <span>
                {{ session('error') }}
            </span>

        </div>

    @endif


    {{-- =====================================================
         FILTROS
    ====================================================== --}}

    <div class="admin-billing-filter">

        <form
            action="{{ route('admin.billing.index') }}"
            method="GET"
            class="admin-billing-filter-form"
        >

            <div class="admin-billing-search">

                <i class="bi bi-search"></i>

                <input
                    type="text"
                    name="numero_pedido"
                    value="{{ $numeroPedido }}"
                    class="admin-billing-search-input"
                    placeholder="Buscar por número de pedido"
                >

            </div>


            <select
                name="estado"
                class="admin-billing-filter-select"
            >

                <option value="">
                    Todos los estados
                </option>

                @foreach($estados as $estadoComprobante)

                    <option
                        value="{{ $estadoComprobante->codigo }}"
                        @selected($estado === $estadoComprobante->codigo)
                    >
                        {{ $estadoComprobante->nombre }}
                    </option>

                @endforeach

            </select>


            <button
                type="submit"
                class="admin-billing-search-button"
            >

                <i class="bi bi-search"></i>

                Buscar

            </button>

        </form>

    </div>


    {{-- =====================================================
         TABLA
    ====================================================== --}}

    <div class="admin-billing-card">

        <div class="admin-billing-table-wrapper">

            <table class="admin-billing-table">

                <thead>

                    <tr>

                        <th>
                            Pedido
                        </th>

                        <th>
                            Cliente
                        </th>

                        <th>
                            Pago
                        </th>

                        <th>
                            Comprobante
                        </th>

                        <th>
                            Estado
                        </th>

                        <th>
                            Fecha
                        </th>

                        <th class="admin-billing-actions-column">
                            Acciones
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($orders as $order)

                        <tr>

                            {{-- =================================================
                                 PEDIDO
                            ================================================== --}}

                            <td>

                                <strong class="admin-billing-order">
                                    {{ $order->numero_pedido }}
                                </strong>

                            </td>


                            {{-- =================================================
                                 CLIENTE
                            ================================================== --}}

                            <td>

                                <div class="admin-billing-client">

                                    <strong>
                                        {{ $order->user?->name ?? 'Sin cliente' }}
                                    </strong>

                                    <span>
                                        {{ $order->user?->email ?? '—' }}
                                    </span>

                                </div>

                            </td>


                            {{-- =================================================
                                 PAGO
                            ================================================== --}}

                            <td>

                                @if($order->payment)

                                    <div class="admin-billing-payment">

                                        <strong>
                                            {{ $order->payment->paymentMethod?->nombre ?? '—' }}
                                        </strong>

                                        <span>
                                            {{ $order->payment->estadoPago?->nombre ?? '—' }}
                                        </span>

                                    </div>

                                @else

                                    <span class="admin-billing-muted">
                                        Sin pago
                                    </span>

                                @endif

                            </td>


                            {{-- =================================================
                                 COMPROBANTE
                            ================================================== --}}

                            <td>

                                @if($order->comprobante)

                                    <div class="admin-billing-document">

                                        <span class="admin-billing-document-type">

                                            {{ $order->comprobante->tipo
                                                ? ucfirst($order->comprobante->tipo)
                                                : 'Comprobante'
                                            }}

                                        </span>

                                        <strong>

                                            {{ $order->comprobante->serie ?? '—' }}

                                            -

                                            {{ $order->comprobante->numero ?? '—' }}

                                        </strong>

                                    </div>

                                @else

                                    <span class="admin-billing-muted">
                                        Sin comprobante
                                    </span>

                                @endif

                            </td>


                            {{-- =================================================
                                 ESTADO
                            ================================================== --}}

                            <td>

                                @if($order->comprobante?->estadoComprobante)

                                    @php
                                        $estadoCodigo = strtolower(
                                            $order->comprobante->estadoComprobante->codigo
                                        );

                                        $estadoClase = match ($estadoCodigo) {
                                            'aceptado' => 'success',
                                            'rechazado' => 'danger',
                                            'anulado' => 'secondary',
                                            'pendiente' => 'warning',
                                            default => 'info',
                                        };
                                    @endphp

                                    <span
                                        class="admin-billing-status admin-billing-status-{{ $estadoClase }}"
                                    >
                                        {{ $order->comprobante->estadoComprobante->nombre }}
                                    </span>

                                @else

                                    <span class="admin-billing-status admin-billing-status-warning">
                                        Pendiente
                                    </span>

                                @endif

                            </td>


                            {{-- =================================================
                                 FECHA
                            ================================================== --}}

                            <td>

                                <span class="admin-billing-date">

                                    {{ $order->created_at->format('d/m/Y H:i') }}

                                </span>

                            </td>


                            {{-- =================================================
                                 ACCIONES
                            ================================================== --}}

                            <td class="admin-billing-actions">

                                <div class="admin-actions">


                                    {{-- APROBAR PAGO EN TIENDA --}}

                                    @if(
                                        $order->payment &&
                                        $order->payment->paymentMethod &&
                                        $order->payment->paymentMethod->codigo === 'PAGO_TIENDA' &&
                                        $order->payment->isPendiente()
                                    )

                                        <form
                                            action="{{ route(
                                                'admin.billing.approve-payment',
                                                $order->id
                                            ) }}"
                                            method="POST"
                                            class="d-inline"
                                        >

                                            @csrf

                                            <button
                                                type="submit"
                                                class="admin-action admin-action-success"
                                                title="Aprobar pago"
                                            >

                                                <i class="bi bi-check-circle"></i>

                                            </button>

                                        </form>

                                    @endif


                                    {{-- PDF --}}

                                    @if(
                                        $order->comprobante?->electronicDocument?->pdf_url
                                    )

                                        <a
                                            href="{{ $order->comprobante->electronicDocument->pdf_url }}"
                                            target="_blank"
                                            class="admin-action admin-action-view"
                                            title="Ver comprobante PDF"
                                            aria-label="Ver comprobante PDF"
                                        >

                                            <i class="bi bi-file-earmark-pdf-fill"></i>

                                        </a>

                                    @endif


                                    {{-- XML --}}

                                    @if(
                                        $order->comprobante?->electronicDocument?->xml_url
                                    )

                                        <a
                                            href="{{ $order->comprobante->electronicDocument->xml_url }}"
                                            target="_blank"
                                            class="admin-action admin-action-success"
                                            title="Descargar XML"
                                            aria-label="Descargar XML"
                                        >

                                            <i class="bi bi-file-earmark-code-fill"></i>

                                        </a>

                                    @endif


                                    {{-- CDR --}}

                                    @if(
                                        $order->comprobante?->electronicDocument?->cdr_url
                                    )

                                        <a
                                            href="{{ $order->comprobante->electronicDocument->cdr_url }}"
                                            target="_blank"
                                            class="admin-action admin-action-warning"
                                            title="Descargar CDR"
                                            aria-label="Descargar CDR"
                                        >

                                            <i class="bi bi-file-earmark-check-fill"></i>

                                        </a>

                                    @endif

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="7"
                                class="admin-billing-empty"
                            >

                                <div class="admin-billing-empty-icon">

                                    <i class="bi bi-receipt"></i>

                                </div>

                                <strong>
                                    No existen pedidos registrados
                                </strong>

                                <span>
                                    No se encontraron pedidos con los filtros seleccionados.
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

            <div class="admin-billing-pagination">

                {{ $orders->links('vendor.pagination.paginacion') }}

            </div>
        @endif

    </div>

</div>

@endsection