@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

<div class="dashboard-page">

    {{-- ==========================================================
        DASHBOARD HEADER
    =========================================================== --}}

    <section class="dashboard-header">

        <div class="dashboard-header-left">

            <span class="dashboard-badge">

                <i class="bi bi-cup-hot-fill"></i>

                Dashboard PROCÁFES

            </span>

            <h1>

                ¡Buenos días,
                {{ auth()->user()->nombre_completo ?? auth()->user()->name }}!
                👋

            </h1>

            <p>

                Bienvenido al panel administrativo de PROCÁFES.

                Desde aquí podrás controlar productos, pedidos,
                clientes, ventas y reportes del sistema.

            </p>

        </div>

        <div class="dashboard-header-right">

            <div class="today-card">

                <span>

                    <i class="bi bi-calendar-event-fill"></i>

                    {{ now()->translatedFormat('l, d \d\e F \d\e Y') }}

                </span>

            </div>

        </div>

    </section>

    {{-- ==========================================================
        KPI CARDS
    =========================================================== --}}


    {{-- ==========================================================
        RESUMEN DE VENTAS
    =========================================================== --}}

    <section class="dashboard-main">

        {{-- ======================================================
            COLUMNA IZQUIERDA
        ======================================================= --}}

        <div class="dashboard-left">

            <div class="dashboard-card">

                <div class="card-header-custom">

                    <div>

                        <span class="section-badge">

                            <i class="bi bi-graph-up-arrow"></i>

                            Ventas

                        </span>

                        <h3>

                            Resumen de Ventas

                        </h3>

                        <p>

                            Consulta el rendimiento de las ventas por año y mes.

                        </p>

                    </div>

                    <form
                        method="GET"
                        class="d-flex align-items-center gap-2 flex-wrap">

                        <select
                            name="year"
                            class="form-select">

                            @foreach($availableYears as $availableYear)

                                <option
                                    value="{{ $availableYear }}"
                                    {{ $year == $availableYear ? 'selected' : '' }}>

                                    {{ $availableYear }}

                                </option>

                            @endforeach

                        </select>

                        <select
                            name="month"
                            class="form-select">

                            <option value="">

                                Todo el año

                            </option>

                            @foreach([
                                1=>'Enero',
                                2=>'Febrero',
                                3=>'Marzo',
                                4=>'Abril',
                                5=>'Mayo',
                                6=>'Junio',
                                7=>'Julio',
                                8=>'Agosto',
                                9=>'Septiembre',
                                10=>'Octubre',
                                11=>'Noviembre',
                                12=>'Diciembre'
                            ] as $numero=>$nombre)

                                <option
                                    value="{{ $numero }}"
                                    {{ $month == $numero ? 'selected' : '' }}>

                                    {{ $nombre }}

                                </option>

                            @endforeach

                        </select>

                        <button
                            type="submit"
                            class="btn btn-danger">

                            <i class="bi bi-funnel-fill"></i>

                        </button>

                    </form>

                </div>

                {{-- KPIs superiores --}}

                <div class="metrics-row">

                    <div class="metric-box">

                        <span>

                            Ingresos

                        </span>

                        <strong>

                            S/ {{ number_format($stats['revenue'],2) }}

                        </strong>

                    </div>

                    <div class="metric-box">

                        <span>

                            Pedidos

                        </span>

                        <strong>

                            {{ number_format($stats['orders']) }}

                        </strong>

                    </div>

                    <div class="metric-box">

                        <span>

                            Clientes

                        </span>

                        <strong>

                            {{ number_format($stats['customers']) }}

                        </strong>

                    </div>

                </div>

                {{-- CHART --}}

                <div class="chart-wrapper">

                    <canvas id="salesChart"></canvas>

                </div>

            </div>

        </div>

        {{-- ======================================================
            COLUMNA DERECHA
        ======================================================= --}}

        <div class="dashboard-right">

            <div class="dashboard-card">

                <div class="card-header-custom">

                    <h4>

                        <i class="bi bi-lightning-charge-fill text-warning"></i>

                        Estadísticas rápidas

                    </h4>

                </div>

                <div class="report-links">

                    <a href="#">

                        <i class="bi bi-cart-fill"></i>

                        <div>

                            <strong>

                                {{ number_format($stats['orders']) }}

                            </strong>

                            <small>

                                Pedidos registrados

                            </small>

                        </div>

                    </a>

                    <a href="#">

                        <i class="bi bi-cash-stack"></i>

                        <div>

                            <strong>

                                S/ {{ number_format($stats['revenue'],2) }}

                            </strong>

                            <small>

                                Ventas acumuladas

                            </small>

                        </div>

                    </a>

                    <a href="#">

                        <i class="bi bi-people-fill"></i>

                        <div>

                            <strong>

                                {{ number_format($stats['customers']) }}

                            </strong>

                            <small>

                                Clientes registrados

                            </small>

                        </div>

                    </a>

                    <a href="#">

                        <i class="bi bi-cup-hot-fill"></i>

                        <div>

                            <strong>

                                {{ number_format($stats['products']) }}

                            </strong>

                            <small>

                                Productos disponibles

                            </small>

                        </div>

                    </a>

                </div>

                <hr>

                <a
                    href="#"
                    class="btn btn-danger w-100">

                    <i class="bi bi-file-earmark-excel-fill"></i>

                    Exportar Excel

                </a>

            </div>

        </div>

    </section>

    {{-- ==========================================================
        PRODUCTOS + STOCK
    =========================================================== --}}

    <section class="dashboard-bottom">

        {{-- ======================================================
            PRODUCTOS MÁS VENDIDOS
        ======================================================= --}}

        <div class="dashboard-card">

            <div class="card-header-custom">

                <div>

                    <span class="section-badge">

                        <i class="bi bi-award-fill"></i>

                        Ranking

                    </span>

                    <h3>

                        Productos más vendidos

                    </h3>

                </div>

            </div>

            @if(count($best))

                <div class="top-products-list">

                    @foreach($best as $product)

                        <div class="product-item">

                            <img
                                src="{{ $product['img'] }}"
                                alt="{{ $product['name'] }}">

                            <div class="product-info">

                                <h5>

                                    {{ $product['name'] }}

                                </h5>

                                <small>

                                    Producto del catálogo

                                </small>

                                <div class="progress mt-2"
                                    style="height:8px;">

                                    <div
                                        class="progress-bar bg-danger"
                                        style="width:{{ min(($product['orders']*10),100) }}%">

                                    </div>

                                </div>

                            </div>

                            <div class="product-total">

                                <strong>

                                    {{ $product['orders'] }}

                                </strong>

                                <span>

                                    Ventas

                                </span>

                            </div>

                        </div>

                    @endforeach

                </div>

            @else

                <div class="empty-state">

                    <i class="bi bi-cup-hot"></i>

                    <p>

                        Todavía no existen productos vendidos.

                    </p>

                </div>

            @endif

        </div>

        {{-- ======================================================
            STOCK BAJO
        ======================================================= --}}

        <div class="dashboard-card">

            <div class="card-header-custom">

                <div>

                    <span class="section-badge">

                        <i class="bi bi-box-seam-fill"></i>

                        Inventario

                    </span>

                    <h3>

                        Stock Bajo

                    </h3>

                </div>

            </div>

            @if(count($stock))

                <div class="activity-list">

                    @foreach($stock as $item)

                        <div class="activity-item">

                            <i
                                class="bi bi-exclamation-circle-fill text-danger">

                            </i>

                            <div class="flex-grow-1">

                                <strong>

                                    {{ $item['name'] }}

                                </strong>

                                <small>

                                    Stock actual:
                                    {{ $item['stock'] }}

                                </small>

                            </div>

                            <span
                                class="badge bg-danger">

                                Crítico

                            </span>

                        </div>

                    @endforeach

                </div>

            @else

                <div class="empty-state">

                    <i
                        class="bi bi-check-circle-fill text-success">

                    </i>

                    <h5>

                        Inventario saludable

                    </h5>

                    <p>

                        Todos los productos tienen suficiente stock.

                    </p>

                </div>

            @endif

        </div>

    </section>
        {{-- ==========================================================
        REPORTES + ACTIVIDAD
    =========================================================== --}}

    <section class="dashboard-bottom">

        {{-- ======================================================
            REPORTES RÁPIDOS
        ======================================================= --}}

        <div class="dashboard-card">

            <div class="card-header-custom">

                <div>

                    <span class="section-badge">

                        <i class="bi bi-file-earmark-bar-graph-fill"></i>

                        Reportes

                    </span>

                    <h3>

                        Reportes rápidos

                    </h3>

                    <p>

                        Accede rápidamente a los principales reportes del sistema.

                    </p>

                </div>

            </div>

            <div class="report-links">

                <a href="#">

                    <i class="bi bi-file-earmark-excel-fill"></i>

                    <div>

                        <strong>

                            Reporte de Ventas

                        </strong>

                        <small>

                            Exportar información de ventas.

                        </small>

                    </div>

                </a>

                <a href="#">

                    <i class="bi bi-box-seam-fill"></i>

                    <div>

                        <strong>

                            Inventario

                        </strong>

                        <small>

                            Productos y existencias.

                        </small>

                    </div>

                </a>

                <a href="#">

                    <i class="bi bi-people-fill"></i>

                    <div>

                        <strong>

                            Clientes

                        </strong>

                        <small>

                            Clientes registrados.

                        </small>

                    </div>

                </a>

                <a href="#">

                    <i class="bi bi-cup-hot-fill"></i>

                    <div>

                        <strong>

                            Productos

                        </strong>

                        <small>

                            Catálogo de productos.

                        </small>

                    </div>

                </a>

            </div>

        </div>





        {{-- ======================================================
            ACTIVIDAD RECIENTE
        ======================================================= --}}

        <div class="dashboard-card">

            <div class="card-header-custom">

                <div>

                    <span class="section-badge">

                        <i class="bi bi-clock-history"></i>

                        Actividad

                    </span>

                    <h3>

                        Actividad reciente

                    </h3>

                    <p>

                        Últimos movimientos registrados en el sistema.

                    </p>

                </div>

            </div>

            @if(count($activities))

                <div class="activity-list">

                    @foreach($activities as $activity)

                        <div class="activity-item">

                            <i class="bi bi-receipt-cutoff text-danger"></i>

                            <div class="flex-grow-1">

                                <strong>

                                    Pedido #{{ $activity['number'] }}

                                </strong>

                                <small>

                                    Cliente:
                                    {{ $activity['customer'] }}

                                </small>

                            </div>

                            <div class="text-end">

                                <strong>

                                    S/
                                    {{ number_format($activity['total'],2) }}

                                </strong>

                            </div>

                        </div>

                    @endforeach

                </div>

            @else

                <div class="empty-state">

                    <i class="bi bi-clock-history"></i>

                    <h5>

                        Sin actividad reciente

                    </h5>

                    <p>

                        Todavía no existen movimientos registrados.

                    </p>

                </div>

            @endif

        </div>

    </section>
    {{-- ==========================================================
    FIN DEL DASHBOARD
========================================================== --}}

</div>

@endsection


@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

document.addEventListener('DOMContentLoaded', function () {

    const canvas = document.getElementById('salesChart');

    if (!canvas) return;

    const ctx = canvas.getContext('2d');

    new Chart(ctx, {

        type: 'line',

        data: {

            labels: @json($labels),

            datasets: [

                {

                    label: 'Ventas',

                    data: @json($revenue),

                    borderColor: '#D62828',

                    backgroundColor: 'rgba(214,40,40,.10)',

                    fill: true,

                    tension: .40,

                    pointRadius: 5,

                    pointHoverRadius: 7,

                    pointBackgroundColor: '#D62828',

                    pointBorderColor: '#ffffff',

                    pointBorderWidth: 2,

                    borderWidth: 3

                }

            ]

        },

        options: {

            responsive: true,

            maintainAspectRatio: false,

            interaction: {

                intersect: false,

                mode: 'index'

            },

            plugins: {

                legend: {

                    display: false

                },

                tooltip: {

                    backgroundColor: '#202020',

                    titleColor: '#fff',

                    bodyColor: '#fff',

                    padding: 12,

                    displayColors: false,

                    callbacks: {

                        label: function(context){

                            return 'Ventas: S/ ' + context.raw;

                        }

                    }

                }

            },

            scales: {

                x: {

                    grid: {

                        display: false

                    },

                    ticks: {

                        color: '#7c7c7c'

                    }

                },

                y: {

                    beginAtZero: true,

                    ticks: {

                        color: '#7c7c7c',

                        callback: function(value){

                            return 'S/ ' + value;

                        }

                    },

                    grid: {

                        color: '#EFEFEF'

                    }

                }

            }

        }

    });

});

</script>

@endpush