@extends('layouts.admin')

@section('title', 'Facturación electrónica | PROCÁFES')

@section('content')

<div class="container-fluid py-2">

    <div class="d-flex flex-column flex-md-row
                justify-content-between
                align-items-md-center
                gap-3 mb-4">

        <div>

            <div class="d-flex align-items-center gap-2 mb-1">

                <div
                    class="rounded-3 p-2"
                    style="
                        background:#FDECEC;
                        color:#D62828;
                    "
                >
                    <i class="bi bi-receipt-cutoff fs-5"></i>
                </div>

                <h1
                    class="h4 fw-bold mb-0"
                    style="color:#3D2C2E;"
                >
                    Facturación electrónica
                </h1>

            </div>

            <p
                class="mb-0 small"
                style="color:#777777;"
            >
                Consulta y administra los comprobantes electrónicos
                generados para los pedidos.
            </p>

        </div>

    </div>


    {{-- =====================================================
         MENSAJES
    ====================================================== --}}

    @if(session('success'))

        <div
            class="alert alert-dismissible fade show border-0 shadow-sm"
            role="alert"
            style="
                background:#E9F8EF;
                color:#18A558;
            "
        >

            <i class="bi bi-check-circle-fill me-2"></i>

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    @if(session('error'))

        <div
            class="alert alert-dismissible fade show border-0 shadow-sm"
            role="alert"
            style="
                background:#FDECEC;
                color:#DC3545;
            "
        >

            <i class="bi bi-exclamation-triangle-fill me-2"></i>

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- =====================================================
         BÚSQUEDA
    ====================================================== --}}

    <div
        class="card border-0 shadow-sm mb-4"
        style="border-radius:14px;"
    >

        <div
            class="card-header bg-white py-3"
            style="
                border-bottom:1px solid #ECE7E2;
                border-radius:14px 14px 0 0;
            "
        >

            <div class="d-flex align-items-center">

                <div
                    class="rounded-3 p-2 me-3"
                    style="
                        background:#FDECEC;
                        color:#D62828;
                    "
                >
                    <i class="bi bi-search"></i>
                </div>

                <div>

                    <h5
                        class="mb-0 fw-semibold"
                        style="color:#3D2C2E;"
                    >
                        Buscar pedido
                    </h5>

                    <small style="color:#777777;">
                        Consulta la información del pedido y su comprobante.
                    </small>

                </div>

            </div>

        </div>


        <div class="card-body">

            <form
                action="{{ route('admin.billing.lookup') }}"
                method="POST"
            >

                @csrf

                <div class="row g-3 align-items-end">

                    <div class="col-md-6 col-lg-5">

                        <label
                            for="numero_pedido"
                            class="form-label fw-semibold"
                            style="color:#3D2C2E;"
                        >
                            Número de pedido
                        </label>

                        <input
                            type="text"
                            name="numero_pedido"
                            id="numero_pedido"
                            class="form-control"
                            placeholder="Ej. PED-20260808-UTDSMS"
                            value="{{ old('numero_pedido') }}"
                            autocomplete="off"
                            style="
                                border-color:#ECE7E2;
                                border-radius:10px;
                            "
                        >

                        @error('numero_pedido')

                            <div
                                class="small mt-1"
                                style="color:#DC3545;"
                            >
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    <div class="col-md-auto">

                        <button
                            type="submit"
                            class="btn px-4"
                            style="
                                background:#D62828;
                                color:#FFFFFF;
                                border-radius:10px;
                            "
                        >

                            <i class="bi bi-search me-1"></i>

                            Buscar pedido

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

{{-- =====================================================
     PAGINACIÓN
====================================================== --}}

@if ($orders->hasPages())

    <div
        class="card-footer bg-white border-0 py-3"
        style="
            border-top:1px solid #ECE7E2 !important;
            border-radius:0 0 14px 14px;
        "
    >

        <div class="d-flex
                    flex-column flex-md-row
                    justify-content-between
                    align-items-center
                    gap-3">

            {{-- Información --}}

            <small style="color:#777777;">

                Mostrando

                <strong style="color:#3D2C2E;">
                    {{ $orders->firstItem() }}
                </strong>

                a

                <strong style="color:#3D2C2E;">
                    {{ $orders->lastItem() }}
                </strong>

                de

                <strong style="color:#3D2C2E;">
                    {{ $orders->total() }}
                </strong>

                pedidos

            </small>


            {{-- Navegación --}}

            <div>

                {{ $orders->links('vendor.pagination.paginacion-admin') }}

            </div>

        </div>

    </div>

@endif
    {{-- =====================================================
         RESUMEN
    ====================================================== --}}

    <div class="row g-3 mb-4">

        {{-- Pedidos --}}

        <div class="col-sm-6 col-xl-3">

            <div
                class="card border-0 shadow-sm h-100"
                style="border-radius:14px;"
            >

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <small style="color:#777777;">
                                Pedidos
                            </small>

                            <h4
                                class="fw-bold mt-1 mb-0"
                                style="color:#3D2C2E;"
                            >
                                {{ $orders->count() }}
                            </h4>

                        </div>

                        <div
                            class="rounded-3 p-2"
                            style="
                                background:#FDECEC;
                                color:#D62828;
                            "
                        >
                            <i class="bi bi-cart-check fs-5"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Emitidos --}}

        <div class="col-sm-6 col-xl-3">

            <div
                class="card border-0 shadow-sm h-100"
                style="border-radius:14px;"
            >

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <small style="color:#777777;">
                                Comprobantes emitidos
                            </small>

                            <h4
                                class="fw-bold mt-1 mb-0"
                                style="color:#3D2C2E;"
                            >
                                {{
                                    $orders->filter(
                                        fn ($order) =>
                                            $order->comprobante?->electronicDocument
                                    )->count()
                                }}
                            </h4>

                        </div>

                        <div
                            class="rounded-3 p-2"
                            style="
                                background:#E9F8EF;
                                color:#18A558;
                            "
                        >
                            <i class="bi bi-file-earmark-check fs-5"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Pendientes --}}

        <div class="col-sm-6 col-xl-3">

            <div
                class="card border-0 shadow-sm h-100"
                style="border-radius:14px;"
            >

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <small style="color:#777777;">
                                Pendientes
                            </small>

                            <h4
                                class="fw-bold mt-1 mb-0"
                                style="color:#3D2C2E;"
                            >
                                {{
                                    $orders->filter(
                                        fn ($order) =>
                                            !$order->comprobante?->electronicDocument
                                    )->count()
                                }}
                            </h4>

                        </div>

                        <div
                            class="rounded-3 p-2"
                            style="
                                background:#FFF8E8;
                                color:#F59E0B;
                            "
                        >
                            <i class="bi bi-clock-history fs-5"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- SUNAT --}}

        <div class="col-sm-6 col-xl-3">

            <div
                class="card border-0 shadow-sm h-100"
                style="border-radius:14px;"
            >

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <small style="color:#777777;">
                                Aceptados SUNAT
                            </small>

                            <h4
                                class="fw-bold mt-1 mb-0"
                                style="color:#3D2C2E;"
                            >
                                {{
                                    $orders->filter(
                                        fn ($order) =>
                                            strtolower(
                                                $order->comprobante
                                                    ?->electronicDocument
                                                    ?->estado ?? ''
                                            ) === 'aceptado'
                                    )->count()
                                }}
                            </h4>

                        </div>

                        <div
                            class="rounded-3 p-2"
                            style="
                                background:#E9F8EF;
                                color:#18A558;
                            "
                        >
                            <i class="bi bi-shield-check fs-5"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =====================================================
         TABLA
    ====================================================== --}}

    <div
        class="card border-0 shadow-sm"
        style="border-radius:14px;"
    >

        <div
            class="card-header bg-white py-3"
            style="
                border-bottom:1px solid #ECE7E2;
                border-radius:14px 14px 0 0;
            "
        >

            <div
                class="d-flex flex-column flex-md-row
                       justify-content-between
                       align-items-md-center gap-2"
            >

                <div>

                    <h5
                        class="mb-0 fw-semibold"
                        style="color:#3D2C2E;"
                    >
                        <i
                            class="bi bi-receipt me-2"
                            style="color:#D62828;"
                        ></i>

                        Pedidos para facturación
                    </h5>

                    <small style="color:#777777;">
                        Comprobantes gestionados mediante NubeFact / API Perú.
                    </small>

                </div>

                <span
                    class="badge rounded-pill px-3 py-2"
                    style="
                        background:#FAF8F5;
                        color:#3D2C2E;
                        border:1px solid #ECE7E2;
                    "
                >
                    {{ $orders->count() }} registros
                </span>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead>

                        <tr
                            style="
                                background:#FAF8F5;
                                color:#3D2C2E;
                            "
                        >

                            <th class="ps-4">
                                Pedido
                            </th>

                            <th>
                                Cliente
                            </th>

                            <th>
                                Fecha
                            </th>

                            <th>
                                Comprobante
                            </th>

                            <th>
                                Estado
                            </th>

                            <th class="text-end pe-4">
                                Documentos
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($orders as $order)

                            <tr>

                                <td class="ps-4">

                                    <strong style="color:#3D2C2E;">
                                        #{{ $order->numero_pedido }}
                                    </strong>

                                    <small
                                        class="d-block"
                                        style="color:#888888;"
                                    >
                                        Pedido #{{ $order->id }}
                                    </small>

                                </td>


                                <td>

                                    <strong style="color:#2B2B2B;">
                                        {{ $order->user?->name ?? 'Cliente' }}
                                    </strong>

                                    @if($order->user?->email)

                                        <small
                                            class="d-block"
                                            style="color:#888888;"
                                        >
                                            {{ $order->user->email }}
                                        </small>

                                    @endif

                                </td>


                                <td>

                                    <span style="color:#2B2B2B;">
                                        {{ $order->created_at->format('d/m/Y') }}
                                    </span>

                                    <small
                                        class="d-block"
                                        style="color:#888888;"
                                    >
                                        {{ $order->created_at->format('H:i') }}
                                    </small>

                                </td>


                                <td>

                                    @if($order->comprobante)

                                        <strong style="color:#3D2C2E;">

                                            {{
                                                ucfirst(
                                                    $order->comprobante
                                                        ->tipo_comprobante
                                                )
                                            }}

                                        </strong>

                                        @if(
                                            $order->comprobante
                                                ->electronicDocument
                                        )

                                            <small
                                                class="d-block"
                                                style="color:#888888;"
                                            >

                                                {{
                                                    $order->comprobante
                                                        ->electronicDocument
                                                        ->numeroCompleto()
                                                }}

                                            </small>

                                        @endif

                                    @else

                                        <span style="color:#888888;">
                                            Sin comprobante
                                        </span>

                                    @endif

                                </td>


                                <td>

                                    @if(
                                        $order->comprobante?->electronicDocument
                                    )

                                        @php

                                            $estado = strtolower(
                                                $order->comprobante
                                                    ->electronicDocument
                                                    ->estado ?? ''
                                            );

                                        @endphp

                                        @if($estado === 'aceptado')

                                            <span
                                                class="badge rounded-pill"
                                                style="
                                                    background:#E9F8EF;
                                                    color:#18A558;
                                                "
                                            >
                                                <i class="bi bi-check-circle me-1"></i>
                                                Aceptado
                                            </span>

                                        @elseif($estado === 'rechazado')

                                            <span
                                                class="badge rounded-pill"
                                                style="
                                                    background:#FDECEC;
                                                    color:#DC3545;
                                                "
                                            >
                                                <i class="bi bi-x-circle me-1"></i>
                                                Rechazado
                                            </span>

                                        @elseif($estado === 'anulado')

                                            <span
                                                class="badge rounded-pill"
                                                style="
                                                    background:#F5F5F5;
                                                    color:#666666;
                                                "
                                            >
                                                Anulado
                                            </span>

                                        @else

                                            <span
                                                class="badge rounded-pill"
                                                style="
                                                    background:#FFF8E8;
                                                    color:#F59E0B;
                                                "
                                            >
                                                <i class="bi bi-clock me-1"></i>
                                                {{ ucfirst($estado ?: 'Pendiente') }}
                                            </span>

                                        @endif

                                    @else

                                        <span
                                            class="badge rounded-pill"
                                            style="
                                                background:#F5F5F5;
                                                color:#777777;
                                            "
                                        >
                                            Pendiente
                                        </span>

                                    @endif

                                </td>


                                <td class="text-end pe-4">

                                    @if(
                                        $order->comprobante?->electronicDocument
                                    )

                                        @php
                                            $document =
                                                $order->comprobante
                                                    ->electronicDocument;
                                        @endphp

                                        <div
                                            class="d-flex
                                                   justify-content-end
                                                   flex-wrap
                                                   gap-1"
                                        >

                                            @if($document->pdf_url)

                                                <a
                                                    href="{{ $document->pdf_url }}"
                                                    target="_blank"
                                                    rel="noopener noreferrer"
                                                    class="btn btn-sm"
                                                    style="
                                                        background:#D62828;
                                                        color:#FFFFFF;
                                                    "
                                                >
                                                    <i class="bi bi-file-earmark-pdf"></i>
                                                    <span class="d-none d-lg-inline">
                                                        PDF
                                                    </span>
                                                </a>

                                            @endif


                                            @if($document->xml_url)

                                                <a
                                                    href="{{ $document->xml_url }}"
                                                    target="_blank"
                                                    rel="noopener noreferrer"
                                                    class="btn btn-sm"
                                                    style="
                                                        background:#FAF8F5;
                                                        color:#3D2C2E;
                                                        border:1px solid #ECE7E2;
                                                    "
                                                >
                                                    <i class="bi bi-filetype-xml"></i>
                                                    <span class="d-none d-lg-inline">
                                                        XML
                                                    </span>
                                                </a>

                                            @endif


                                            @if($document->cdr_url)

                                                <a
                                                    href="{{ $document->cdr_url }}"
                                                    target="_blank"
                                                    rel="noopener noreferrer"
                                                    class="btn btn-sm"
                                                    style="
                                                        background:#E9F8EF;
                                                        color:#18A558;
                                                        border:1px solid #18A558;
                                                    "
                                                >
                                                    <i class="bi bi-file-earmark-check"></i>
                                                    <span class="d-none d-lg-inline">
                                                        CDR
                                                    </span>
                                                </a>

                                            @endif


                                            @if(
                                                !$document->pdf_url &&
                                                !$document->xml_url &&
                                                !$document->cdr_url
                                            )

                                                <small
                                                    style="color:#888888;"
                                                >
                                                    Procesando...
                                                </small>

                                            @endif

                                        </div>

                                    @else

                                        <small style="color:#888888;">
                                            No generado
                                        </small>

                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="6"
                                    class="text-center py-5"
                                >

                                    <i
                                        class="bi bi-receipt display-6"
                                        style="color:#D9D2CB;"
                                    ></i>

                                    <h5
                                        class="mt-3 mb-1"
                                        style="color:#3D2C2E;"
                                    >
                                        No hay pedidos
                                    </h5>

                                    <p
                                        class="mb-0"
                                        style="color:#888888;"
                                    >
                                        No existen pedidos disponibles
                                        para facturación.
                                    </p>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection