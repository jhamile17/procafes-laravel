@extends('layouts.admin')

@section('title', 'Órdenes | PROCAFES')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="fw-bold mb-1">
                Gestión de Órdenes
            </h2>

            <p class="text-muted mb-0">
                Administra los pedidos realizados por los clientes.
            </p>

        </div>

    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('info'))
        <div class="alert alert-info">
            {{ session('info') }}
        </div>
    @endif

    <div class="card shadow-sm border-0 mb-4">

        <div class="card-body">

            <form method="GET">

                <div class="row g-3">

                    <div class="col-md-6">

                        <input
                            type="text"
                            class="form-control"
                            name="q"
                            value="{{ $q }}"
                            placeholder="Buscar por número de pedido, cliente o correo">

                    </div>

                    <div class="col-md-4">

                        <select
                            name="status"
                            class="form-select">

                            <option value="">
                                Todos los estados
                            </option>

                            @foreach($statuses as $codigo)

                                <option
                                    value="{{ $codigo }}"
                                    @selected($status==$codigo)>

                                    {{ $statusLabel[$codigo] }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="col-md-2 d-grid">

                        <button class="btn btn-dark">

                            <i class="bi bi-search me-2"></i>

                            Buscar

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

    <div class="card shadow-sm border-0">

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">

                    <tr>

                        <th># Pedido</th>

                        <th>Cliente</th>

                        <th>Estado</th>

                        <th>Total</th>

                        <th>Tipo entrega</th>

                        <th>Fecha</th>

                        <th width="100">

                            Acciones

                        </th>

                    </tr>

                </thead>

                <tbody>

                @forelse($orders as $order)

                    <tr>

                        <td>

                            <strong>

                                {{ $order->numero_pedido }}

                            </strong>

                        </td>

                        <td>

                            <div class="fw-semibold">

                                {{ $order->user?->name }}

                            </div>

                            <small class="text-muted">

                                {{ $order->user?->email }}

                            </small>

                        </td>

                        <td>

                            <form
                                action="{{ route('admin.orders.status',$order) }}"
                                method="POST">

                                @csrf

                                @method('PATCH')

                                <select
                                    name="estado_pedido_id"
                                    class="form-select form-select-sm"
                                    onchange="this.form.submit()">

                                    @foreach(\App\Models\EstadoPedido::where('status',1)->get() as $estado)

                                        <option
                                            value="{{ $estado->id }}"
                                            @selected($estado->id==$order->estado_pedido_id)>

                                            {{ $estado->nombre }}

                                        </option>

                                    @endforeach

                                </select>

                            </form>

                        </td>

                        <td>

                            S/

                            {{ number_format($order->total_price,2) }}

                        </td>

                        <td>

                            {{ ucfirst($order->delivery_type) }}

                        </td>

                        <td>

                            {{ $order->created_at->format('d/m/Y H:i') }}

                        </td>

                        <td>

                            <a
                                href="{{ route('admin.orders.show',$order) }}"
                                class="btn btn-primary btn-sm">

                                <i class="bi bi-eye"></i>

                            </a>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="7"
                            class="text-center py-5">

                            <i class="bi bi-inbox fs-1 d-block mb-3 text-secondary"></i>

                            No existen órdenes registradas.

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

        @if($orders->hasPages())

            <div class="card-footer">

                {{ $orders->links() }}

            </div>

        @endif

    </div>

</div>

@endsection