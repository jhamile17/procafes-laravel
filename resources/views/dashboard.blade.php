@extends('admin.layout')

@section('title', 'Dashboard | PROCAFES')

@section('content')

<div class="container-fluid">

    {{-- Encabezado --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="fw-bold mb-1">
                Panel de Administración
            </h2>

            <p class="text-muted mb-0">
                Bienvenido al sistema de gestión de PROCAFES.
            </p>

        </div>

        <div>

            <a
                href="{{ route('home') }}"
                target="_blank"
                class="btn btn-outline-secondary">

                <i class="bi bi-shop me-1"></i>

                Ver tienda

            </a>

        </div>

    </div>

    {{-- Tarjetas --}}
    <div class="row g-4 mb-4">

        {{-- Productos --}}
        <div class="col-md-6 col-xl-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <small class="text-muted">
                                Productos
                            </small>

                            <h3 class="fw-bold mb-0">

                                {{ number_format($productos) }}

                            </h3>

                        </div>

                        <div class="fs-1 text-primary">

                            <i class="bi bi-box-seam"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        {{-- Clientes --}}
        <div class="col-md-6 col-xl-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <small class="text-muted">
                                Clientes
                            </small>

                            <h3 class="fw-bold mb-0">

                                {{ number_format($clientes) }}

                            </h3>

                        </div>

                        <div class="fs-1 text-success">

                            <i class="bi bi-people"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        {{-- Pedidos --}}
        <div class="col-md-6 col-xl-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <small class="text-muted">
                                Pedidos
                            </small>

                            <h3 class="fw-bold mb-0">

                                {{ number_format($pedidos) }}

                            </h3>

                        </div>

                        <div class="fs-1 text-warning">

                            <i class="bi bi-cart-check"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        {{-- Ventas --}}
        <div class="col-md-6 col-xl-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <small class="text-muted">
                                Ventas
                            </small>

                            <h3 class="fw-bold mb-0">

                                S/ {{ number_format($ventas,2) }}

                            </h3>

                        </div>

                        <div class="fs-1 text-danger">

                            <i class="bi bi-cash-stack"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- Accesos rápidos --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0">

                Accesos rápidos

            </h5>

        </div>

        <div class="card-body">

            <div class="row g-3">

                <div class="col-md-3">

                    <a
                        href="{{ route('admin.products.index') }}"
                        class="btn btn-primary w-100">

                        <i class="bi bi-box me-2"></i>

                        Productos

                    </a>

                </div>

                <div class="col-md-3">

                    <a
                        href="{{ route('admin.categories.index') }}"
                        class="btn btn-success w-100">

                        <i class="bi bi-tags me-2"></i>

                        Categorías

                    </a>

                </div>

                <div class="col-md-3">

                    <a
                        href="{{ route('admin.brands.index') }}"
                        class="btn btn-warning w-100">

                        <i class="bi bi-award me-2"></i>

                        Marcas

                    </a>

                </div>

                <div class="col-md-3">

                    <a
                        href="{{ route('admin.users.index') }}"
                        class="btn btn-dark w-100">

                        <i class="bi bi-people-fill me-2"></i>

                        Usuarios

                    </a>

                </div>

            </div>

        </div>

    </div>

        {{-- Productos con stock bajo + Últimos productos --}}
    <div class="row">

        {{-- Productos con stock bajo --}}
        <div class="col-lg-6 mb-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white d-flex justify-content-between align-items-center">

                    <h5 class="mb-0">

                        <i class="bi bi-exclamation-triangle text-warning me-2"></i>

                        Productos con Stock Bajo

                    </h5>

                    <a
                        href="{{ route('admin.products.index') }}"
                        class="btn btn-sm btn-outline-primary">

                        Ver todos

                    </a>

                </div>

                <div class="card-body p-0">

                    @forelse($productosStockBajo as $producto)

                        <div class="d-flex align-items-center justify-content-between p-3 border-bottom">

                            <div class="d-flex align-items-center">

                                <img
                                    src="{{ $producto->image_url }}"
                                    alt="{{ $producto->name }}"
                                    class="rounded"
                                    style="width:60px;height:60px;object-fit:cover;">

                                <div class="ms-3">

                                    <h6 class="mb-1">

                                        {{ $producto->name }}

                                    </h6>

                                    <small class="text-muted">

                                        SKU:
                                        {{ $producto->sku }}

                                    </small>

                                </div>

                            </div>

                            <div class="text-end">

                                <span class="badge bg-danger">

                                    {{ $producto->stock }}

                                </span>

                                <br>

                                <small class="text-muted">

                                    mínimo:
                                    {{ $producto->stock_minimo }}

                                </small>

                            </div>

                        </div>

                    @empty

                        <div class="text-center py-5">

                            <i class="bi bi-check-circle text-success fs-1"></i>

                            <p class="mt-3 mb-0">

                                No existen productos con stock bajo.

                            </p>

                        </div>

                    @endforelse

                </div>

            </div>

        </div>

        {{-- Últimos productos --}}
        <div class="col-lg-6 mb-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white d-flex justify-content-between align-items-center">

                    <h5 class="mb-0">

                        <i class="bi bi-clock-history text-primary me-2"></i>

                        Últimos Productos

                    </h5>

                    <a
                        href="{{ route('admin.products.index') }}"
                        class="btn btn-sm btn-outline-primary">

                        Ver catálogo

                    </a>

                </div>

                <div class="card-body p-0">

                    @forelse($ultimosProductos as $producto)

                        <div class="d-flex align-items-center justify-content-between p-3 border-bottom">

                            <div class="d-flex align-items-center">

                                <img
                                    src="{{ $producto->image_url }}"
                                    alt="{{ $producto->name }}"
                                    class="rounded"
                                    style="width:60px;height:60px;object-fit:cover;">

                                <div class="ms-3">

                                    <h6 class="mb-1">

                                        {{ $producto->name }}

                                    </h6>

                                    <small class="text-muted">

                                        {{ $producto->category?->name ?? 'Sin categoría' }}

                                    </small>

                                    <br>

                                    <small class="text-muted">

                                        {{ $producto->brand?->name ?? 'Sin marca' }}

                                    </small>

                                </div>

                            </div>

                            <div class="text-end">

                                <strong>

                                    {{ $producto->precio_formateado }}

                                </strong>

                                <br>

                                @if($producto->status)

                                    <span class="badge bg-success">

                                        Activo

                                    </span>

                                @else

                                    <span class="badge bg-secondary">

                                        Inactivo

                                    </span>

                                @endif

                            </div>

                        </div>

                    @empty

                        <div class="text-center py-5">

                            <i class="bi bi-box text-secondary fs-1"></i>

                            <p class="mt-3 mb-0">

                                No existen productos registrados.

                            </p>

                        </div>

                    @endforelse

                </div>

            </div>

        </div>

    </div>

        {{-- Información del sistema --}}
    <div class="row">

        {{-- Resumen del sistema --}}
        <div class="col-lg-8 mb-4">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white">

                    <h5 class="mb-0">

                        <i class="bi bi-bar-chart-line me-2"></i>

                        Resumen del Sistema

                    </h5>

                </div>

                <div class="card-body">

                    <div class="row text-center">

                        <div class="col-md-3 mb-3">

                            <div class="border rounded p-3 h-100">

                                <i class="bi bi-box-seam fs-1 text-primary"></i>

                                <h4 class="mt-2">

                                    {{ number_format($productos) }}

                                </h4>

                                <small class="text-muted">

                                    Productos registrados

                                </small>

                            </div>

                        </div>

                        <div class="col-md-3 mb-3">

                            <div class="border rounded p-3 h-100">

                                <i class="bi bi-cart-check fs-1 text-success"></i>

                                <h4 class="mt-2">

                                    {{ number_format($pedidos) }}

                                </h4>

                                <small class="text-muted">

                                    Pedidos registrados

                                </small>

                            </div>

                        </div>

                        <div class="col-md-3 mb-3">

                            <div class="border rounded p-3 h-100">

                                <i class="bi bi-people fs-1 text-warning"></i>

                                <h4 class="mt-2">

                                    {{ number_format($clientes) }}

                                </h4>

                                <small class="text-muted">

                                    Clientes registrados

                                </small>

                            </div>

                        </div>

                        <div class="col-md-3 mb-3">

                            <div class="border rounded p-3 h-100">

                                <i class="bi bi-cash-coin fs-1 text-danger"></i>

                                <h4 class="mt-2">

                                    S/ {{ number_format($ventas,2) }}

                                </h4>

                                <small class="text-muted">

                                    Ventas acumuladas

                                </small>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        {{-- Información del administrador --}}
        <div class="col-lg-4 mb-4">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white">

                    <h5 class="mb-0">

                        <i class="bi bi-person-circle me-2"></i>

                        Administración

                    </h5>

                </div>

                <div class="card-body">

                    <div class="mb-3">

                        <strong>

                            Administradores

                        </strong>

                    </div>

                    <h2 class="fw-bold text-primary">

                        {{ number_format($administradores) }}

                    </h2>

                    <hr>

                    <p class="text-muted mb-3">

                        Bienvenido al panel administrativo de <strong>PROCAFES</strong>.
                    </p>

                    <a
                        href="{{ route('admin.users.index') }}"
                        class="btn btn-primary w-100">

                        <i class="bi bi-people-fill me-2"></i>

                        Administrar Usuarios

                    </a>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection