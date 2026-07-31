@extends('layouts.admin')

@section('title','Detalle del Pedido | PROCÁFES')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="fw-bold mb-1">

                Pedido {{ $order->numero_pedido }}

            </h2>

            <p class="text-muted mb-0">

                Información completa del pedido.

            </p>

        </div>

        <a
            href="{{ route('admin.orders.index') }}"
            class="btn btn-outline-secondary">

            <i class="bi bi-arrow-left me-2"></i>

            Volver

        </a>

    </div>

    <div class="row">

        <div class="col-lg-4">

            <div class="card shadow-sm border-0 mb-4">

                <div class="card-header">

                    <strong>

                        Cliente

                    </strong>

                </div>

                <div class="card-body">

                    <p>

                        <strong>Nombre:</strong><br>

                        {{ $order->user?->name }}

                    </p>

                    <p>

                        <strong>Email:</strong><br>

                        {{ $order->user?->email }}

                    </p>

                    <p>

                        <strong>Estado:</strong><br>

                        <span class="badge bg-success">

                            {{ $order->estadoPedido?->nombre }}

                        </span>

                    </p>

                    <p>

                        <strong>Entrega:</strong><br>

                        {{ ucfirst($order->delivery_type) }}

                    </p>

                    <p>

                        <strong>Fecha:</strong><br>

                        {{ $order->created_at->format('d/m/Y H:i') }}

                    </p>

                </div>

            </div>

            <div class="card shadow-sm border-0">

                <div class="card-header">

                    <strong>

                        Dirección

                    </strong>

                </div>

                <div class="card-body">

                    <p>

                        {{ $order->delivery_alias }}

                    </p>

                    <p>

                        {{ $order->delivery_direccion }}

                    </p>

                    <p>

                        {{ $order->delivery_distrito }}

                        -

                        {{ $order->delivery_provincia }}

                    </p>

                    <p>

                        {{ $order->delivery_departamento }}

                    </p>

                    @if($order->delivery_referencia)

                        <hr>

                        <strong>

                            Referencia

                        </strong>

                        <p>

                            {{ $order->delivery_referencia }}

                        </p>

                    @endif

                </div>

            </div>

        </div>

        <div class="col-lg-8">

            <div class="card shadow-sm border-0">

                <div class="card-header">

                    <strong>

                        Productos

                    </strong>

                </div>

                <div class="card-body p-0">

                    <div class="table-responsive">

                        <table class="table table-hover mb-0">

                            <thead class="table-light">

                                <tr>

                                    <th>Producto</th>

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

                            @foreach($items as $item)

                                <tr>

                                    <td>

                                        {{ $item->product?->name }}

                                    </td>

                                    <td class="text-center">

                                        {{ $item->quantity }}

                                    </td>

                                    <td class="text-end">

                                        S/
                                        {{ number_format($item->unit_price,2) }}

                                    </td>

                                    <td class="text-end">

                                        S/
                                        {{ number_format($item->subtotal,2) }}

                                    </td>

                                </tr>

                            @endforeach

                            </tbody>

                            <tfoot>

                                <tr>

                                    <th colspan="3" class="text-end">

                                        Total

                                    </th>

                                    <th class="text-end">

                                        S/
                                        {{ number_format($order->total_price,2) }}

                                    </th>

                                </tr>

                            </tfoot>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection