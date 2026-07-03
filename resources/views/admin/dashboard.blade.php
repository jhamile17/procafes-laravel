@extends('layouts.admin')

@section('title','Dashboard')

@section('content')

<div class="fade-up">

    {{-- ==========================================
        HEADER
    =========================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="fw-bold mb-1">

                Dashboard PROCÁFES

            </h2>

            <p class="text-muted mb-0">

                Bienvenido al panel administrativo.

            </p>

        </div>

        <div>

            <span class="badge bg-dark">

                {{ now()->translatedFormat('d \\d\\e F \\d\\e Y') }}

            </span>

        </div>

    </div>

    {{-- ==========================================
        KPI
    =========================================== --}}

    <div class="dashboard-stats">

        {{-- Productos --}}

        <div class="stat-card products">

            <div class="stat-top">

                <div>

                    <span class="stat-badge">

                        Catálogo

                    </span>

                </div>

                <div class="stat-icon">

                    <i class="bi bi-box-seam"></i>

                </div>

            </div>

            <h2>

                {{ number_format($stats['products']) }}

            </h2>

            <p>

                Productos registrados

            </p>

            <a href="{{ route('admin.products.index') }}">

                Ver productos

                <i class="bi bi-arrow-right"></i>

            </a>

        </div>

        {{-- Clientes --}}

        <div class="stat-card customers">

            <div class="stat-top">

                <div>

                    <span class="stat-badge">

                        Clientes

                    </span>

                </div>

                <div class="stat-icon">

                    <i class="bi bi-people"></i>

                </div>

            </div>

            <h2>

                {{ number_format($stats['customers']) }}

            </h2>

            <p>

                Clientes registrados

            </p>

            <a href="{{ route('admin.users.index') }}">

                Ver usuarios

                <i class="bi bi-arrow-right"></i>

            </a>

        </div>

        {{-- Pedidos --}}

        <div class="stat-card orders">

            <div class="stat-top">

                <div>

                    <span class="stat-badge">

                        Pedidos

                    </span>

                </div>

                <div class="stat-icon">

                    <i class="bi bi-cart-check"></i>

                </div>

            </div>

            <h2>

                {{ number_format($stats['orders']) }}

            </h2>

            <p>

                Pedidos realizados

            </p>

            <a href="{{ route('admin.orders.index') }}">

                Ver pedidos

                <i class="bi bi-arrow-right"></i>

            </a>

        </div>

        {{-- Ventas --}}

        <div class="stat-card sales">

            <div class="stat-top">

                <div>

                    <span class="stat-badge">

                        Ventas

                    </span>

                </div>

                <div class="stat-icon">

                    <i class="bi bi-cash-stack"></i>

                </div>

            </div>

            <h2>

                S/ {{ number_format($stats['revenue'],2) }}

            </h2>

            <p>

                Ingresos acumulados

            </p>

            <a href="{{ route('admin.billing.index') }}">

                Ver facturación

                <i class="bi bi-arrow-right"></i>

            </a>

        </div>

    </div>

        {{-- ==========================================
        GRÁFICO + CATEGORÍAS
    =========================================== --}}

    <div class="dashboard-grid">

        {{-- ===========================
            REPORTE DE VENTAS
        ============================ --}}

        <div class="sales-card">

            <div class="sales-header">

                <div>

                    <span class="sales-label">

                        Dashboard

                    </span>

                    <h3>

                        Resumen de Ventas

                    </h3>

                    <p>

                        Evolución de ventas de PROCÁFES

                    </p>

                </div>

                <div class="sales-actions">

                    <button class="btn-period active">

                        Mes

                    </button>

                    <button class="btn-period">

                        Año

                    </button>

                </div>

            </div>

            {{-- ===========================
                MÉTRICAS
            ============================ --}}

            <div class="sales-metrics">

                <div class="metric-item">

                    <span>

                        Ventas

                    </span>

                    <h4>

                        S/
                        {{ number_format($stats['revenue'],2) }}

                    </h4>

                </div>

                <div class="metric-item">

                    <span>

                        Pedidos

                    </span>

                    <h4>

                        {{ $stats['orders'] }}

                    </h4>

                </div>

                <div class="metric-item">

                    <span>

                        Productos

                    </span>

                    <h4>

                        {{ $stats['products'] }}

                    </h4>

                </div>

            </div>

            {{-- ===========================
                CHART
            ============================ --}}

            <div class="sales-chart-container">

                <canvas id="revenueChart"></canvas>

            </div>

        </div>

        {{-- ===========================
            CATEGORÍAS
        ============================ --}}

        <div class="top-products">

            <h3>

                Categorías

            </h3>

            <div class="dashboard-categories">

                @forelse($categories as $category)

                    <span class="category-chip">

                        {{ $category->name }}

                    </span>

                @empty

                    <span class="text-muted">

                        No existen categorías.

                    </span>

                @endforelse

            </div>

            <hr class="my-4">

            <div class="text-center">

                <i class="bi bi-tags display-4 text-secondary"></i>

                <p class="text-muted mt-3 mb-0">

                    Gestiona las categorías de productos.

                </p>

            </div>

        </div>

    </div>

        {{-- ==========================================
        PRODUCTOS MÁS VENDIDOS
    =========================================== --}}

    <div class="row mt-4">

        <div class="col-lg-8">

            <div class="top-products">

                <div class="d-flex justify-content-between align-items-center mb-4">

                    <h3 class="mb-0">

                        Productos más vendidos

                    </h3>

                    <a
                        href="{{ route('admin.products.index') }}"
                        class="btn btn-sm btn-outline-primary">

                        Ver catálogo

                    </a>

                </div>

                @forelse($best as $product)

                    <div class="top-product">

                        <img
                            src="{{ $product['img'] }}"
                            alt="{{ $product['name'] }}">

                        <div class="top-product-info">

                            <strong>

                                {{ $product['name'] }}

                            </strong>

                            <small>

                                {{ $product['orders'] }} unidades vendidas

                            </small>

                        </div>

                        <div class="text-end">

                            <strong>

                                S/
                                {{ number_format($product['total'],2) }}

                            </strong>

                        </div>

                    </div>

                @empty

                    <div class="text-center py-5">

                        <i class="bi bi-box-seam display-4 text-secondary"></i>

                        <p class="text-muted mt-3">

                            Todavía no existen ventas registradas.

                        </p>

                    </div>

                @endforelse

            </div>

        </div>

        {{-- ==========================================
            PANEL DERECHO
        =========================================== --}}

        <div class="col-lg-4">

            <div class="card h-100">

                <div class="card-header">

                    <h5 class="mb-0">

                        Actividad del sistema

                    </h5>

                </div>

                <div class="card-body">

                    <div class="mb-4">

                        <i class="bi bi-check-circle-fill text-success me-2"></i>

                        Productos registrados:

                        <strong>

                            {{ $stats['products'] }}

                        </strong>

                    </div>

                    <div class="mb-4">

                        <i class="bi bi-cart-check-fill text-primary me-2"></i>

                        Pedidos realizados:

                        <strong>

                            {{ $stats['orders'] }}

                        </strong>

                    </div>

                    <div class="mb-4">

                        <i class="bi bi-people-fill text-warning me-2"></i>

                        Clientes registrados:

                        <strong>

                            {{ $stats['customers'] }}

                        </strong>

                    </div>

                    <div>

                        <i class="bi bi-cash-stack text-success me-2"></i>

                        Ventas acumuladas:

                        <strong>

                            S/
                            {{ number_format($stats['revenue'],2) }}

                        </strong>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection

@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

document.addEventListener('DOMContentLoaded', function(){

    const canvas = document.getElementById('revenueChart');

    if(!canvas){

        return;

    }

    const ctx = canvas.getContext('2d');

    const labels = @json($labels);

    const revenue = @json($revenue);

    new Chart(ctx,{

        type:'line',

        data:{

            labels:labels,

            datasets:[{

                label:'Ventas',

                data:revenue,

                borderColor:'#D62828',

                backgroundColor:'rgba(214,40,40,.10)',

                fill:true,

                tension:.4,

                borderWidth:3,

                pointRadius:4,

                pointBackgroundColor:'#D62828'

            }]

        },

        options:{

            responsive:true,

            maintainAspectRatio:false,

            plugins:{

                legend:{

                    display:false

                }

            },

            scales:{

                y:{

                    beginAtZero:true,

                    ticks:{

                        callback:function(value){

                            return "S/ "+value;

                        }

                    }

                }

            }

        }

    });

});

</script>

@endpush