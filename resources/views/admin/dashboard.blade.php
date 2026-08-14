@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

<div class="dashboard-page">

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
                Desde aquí podrás administrar productos, pedidos,
                clientes, ventas y reportes.
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

        <section class="dashboard-kpis">

        <div class="kpi-card">

            <div class="kpi-top">

                <span>Ventas</span>

                <div class="kpi-icon red">
                    <i class="bi bi-cash-stack"></i>
                </div>

            </div>

            <h2>
                S/ {{ number_format($stats['revenue'],2) }}
            </h2>

            <p>Ingresos acumulados</p>

        </div>

        <div class="kpi-card">

            <div class="kpi-top">

                <span>Pedidos</span>

                <div class="kpi-icon blue">
                    <i class="bi bi-cart-check-fill"></i>
                </div>

            </div>

            <h2>
                {{ number_format($stats['orders']) }}
            </h2>

            <p>Pedidos registrados</p>

        </div>

        <div class="kpi-card">

            <div class="kpi-top">

                <span>Clientes</span>

                <div class="kpi-icon gold">
                    <i class="bi bi-people-fill"></i>
                </div>

            </div>

            <h2>
                {{ number_format($stats['customers']) }}
            </h2>

            <p>Clientes registrados</p>

        </div>

        <div class="kpi-card">

            <div class="kpi-top">

                <span>Productos</span>

                <div class="kpi-icon green">
                    <i class="bi bi-cup-hot-fill"></i>
                </div>

            </div>

            <h2>
                {{ number_format($stats['products']) }}
            </h2>

            <p>Productos disponibles</p>

        </div>

    </section>

        <section class="dashboard-main">

            {{-- ===========================
                COLUMNA IZQUIERDA
            ============================ --}}
            <div class="dashboard-left">

                <div class="dashboard-card">

                    @php
                        $meses = [
                            1 => 'Enero',
                            2 => 'Febrero',
                            3 => 'Marzo',
                            4 => 'Abril',
                            5 => 'Mayo',
                            6 => 'Junio',
                            7 => 'Julio',
                            8 => 'Agosto',
                            9 => 'Septiembre',
                            10 => 'Octubre',
                            11 => 'Noviembre',
                            12 => 'Diciembre',
                        ];

                        $mesSeleccionado = $month ? $meses[$month] : 'Todo el año';

                        $totalVentas = $stats['revenue'] ?? 0;
                        $totalPedidos = $stats['orders'] ?? 0;

                        $ticketPromedio = $totalPedidos > 0
                            ? $totalVentas / $totalPedidos
                            : 0;

                        $hayVentas = $totalPedidos > 0;
                    @endphp

                    <div class="card-header-custom">

                        <div>

                            <span class="section-badge">
                                <i class="bi bi-graph-up-arrow"></i>
                                Ventas
                            </span>

                            <h3>Resumen de Ventas</h3>

                            <p class="text-muted mb-3">
                                Mostrando ventas correspondientes a
                                <strong>{{ $mesSeleccionado }} {{ $year }}</strong>
                            </p>

                            @unless($hayVentas)

                                <div class="alert alert-warning mt-3 mb-0">

                                    <i class="bi bi-exclamation-circle-fill me-2"></i>

                                    No existen ventas para este período.

                                </div>

                            @endunless

                        </div>

                        <form method="GET" class="card-actions">

                            <select name="year" class="form-select">

                                @foreach($availableYears as $availableYear)

                                    <option value="{{ $availableYear }}"
                                        {{ $year == $availableYear ? 'selected' : '' }}>

                                        {{ $availableYear }}

                                    </option>

                                @endforeach

                            </select>

                            <select name="month" class="form-select">

                                <option value=""
                                    {{ empty($month) ? 'selected' : '' }}>

                                    Todo el año

                                </option>

                                @foreach($meses as $numero => $nombre)

                                    <option value="{{ $numero }}"
                                        {{ $month == $numero ? 'selected' : '' }}>

                                        {{ $nombre }}

                                    </option>

                                @endforeach

                            </select>

                            <button
                                type="submit"
                                class="btn btn-danger">

                                <i class="bi bi-funnel-fill me-2"></i>

                                Filtrar

                            </button>

                        </form>

                    </div>

                    <div class="chart-wrapper">

                        <canvas id="salesChart"></canvas>

                        @unless($hayVentas)

                            <div class="empty-chart-message">

                                <i class="bi bi-exclamation-circle-fill"></i>

                                <h5>No existen ventas</h5>

                                <p>
                                    No se registraron ventas para
                                    {{ $mesSeleccionado }} {{ $year }}.
                                </p>

                            </div>

                        @endunless

                    </div>

                </div>

            </div>

            {{-- ===========================
                COLUMNA DERECHA
            ============================ --}}
            <div class="dashboard-right">

                <div class="dashboard-card reports-card">

                    <span class="section-badge">

                        <i class="bi bi-file-earmark-excel-fill"></i>

                        Centro de Reportes

                    </span>

                    <h3 class="mt-3">

                        Exportar información

                    </h3>

                    <p class="text-muted">

                        Descarga reportes para analizar el rendimiento de PROCÁFES.

                    </p>

                    <form
                        id="reportForm"
                        method="GET"
                        action="{{ route('admin.reports.sales') }}">

                        <div class="mb-3">

                            <label class="form-label">

                                Reporte

                            </label>

                            <select
                                id="reportType"
                                class="form-select">

                                <option value="sales">
                                    📈 Ventas detalladas
                                </option>

                                <option value="best-sellers">
                                    🏆 Productos más vendidos
                                </option>

                                <option value="least-sellers">
                                    📉 Productos menos vendidos
                                </option>

                                <option value="inventory">
                                    📦 Inventario crítico
                                </option>

                                <option value="categories">
                                    🗂 Ventas por categoría
                                </option>

                                <option value="products">
                                    ☕ Inventario completo
                                </option>

                                <option value="orders">
                                    🧾 Órdenes
                                </option>

                            </select>

                        </div>

                        <div id="fromGroup" class="mb-3">
                            <label class="form-label">
                                Desde
                            </label>

                            <input
                                type="date"
                                name="from"
                                class="form-control">
                        </div>

                        <div id="toGroup" class="mb-4">
                            <label class="form-label">
                                Hasta
                            </label>

                            <input
                                type="date"
                                name="to"
                                class="form-control">
                        </div>

                        <div class="report-preview">

                            <h6 class="mb-3">
                                <i class="bi bi-info-circle-fill text-primary me-2"></i>
                                Información del reporte
                            </h6>

                            <div class="preview-item">

                                <i class="bi bi-file-earmark-excel-fill text-success"></i>

                                <span>Formato Excel (.xlsx)</span>

                            </div>

                            <div class="preview-item">

                                <i class="bi bi-check-circle-fill text-success"></i>

                                <span>Incluye encabezados automáticos</span>

                            </div>

                            <div class="preview-item">

                                <i class="bi bi-table text-primary"></i>

                                <span>Compatible con Excel y Google Sheets</span>

                            </div>

                            <div class="preview-item">

                                <i class="bi bi-clock-history text-warning"></i>

                                <span>Generación inmediata del archivo</span>

                            </div>

                        </div>

                        <button
                            type="submit"
                            class="btn btn-success w-100">

                            <i class="bi bi-download me-2"></i>

                                Generar Reporte Excel

                        </button>

                    </form>

                </div>

            </div>

        </section>

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

<script>
    document.addEventListener("DOMContentLoaded", function () {

        const reportForm = document.getElementById("reportForm");
        const reportType = document.getElementById("reportType");

        const fromGroup = document.getElementById("fromGroup");
        const toGroup   = document.getElementById("toGroup");

        function updateForm() {

            const value = reportType.value;

            switch (value) {

                case "sales":
                    reportForm.action = "{{ route('admin.reports.sales') }}";
                    break;

                case "best-sellers":
                    reportForm.action = "{{ route('admin.reports.best-sellers') }}";
                    break;

                case "least-sellers":
                    reportForm.action = "{{ route('admin.reports.least-sellers') }}";
                    break;

                case "inventory":
                    reportForm.action = "{{ route('admin.reports.inventory') }}";
                    break;

                case "categories":
                    reportForm.action = "{{ route('admin.reports.categories') }}";
                    break;

                case "products":
                    reportForm.action = "{{ route('admin.reports.products') }}";
                    break;

                case "orders":
                    reportForm.action = "{{ route('admin.reports.orders') }}";
                    break;
            }

            const reportsWithDates = [
                "sales",
                "best-sellers",
                "least-sellers",
                "categories",
                "orders"
            ];

            if (reportsWithDates.includes(value)) {
                fromGroup.style.display = "";
                toGroup.style.display = "";
            } else {
                fromGroup.style.display = "none";
                toGroup.style.display = "none";
            }

        }

        updateForm();

        reportType.addEventListener("change", updateForm);

    });
</script>

@endpush