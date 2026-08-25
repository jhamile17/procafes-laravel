@extends('layouts.admin')

@section('title', 'Dashboard | PROCÁFES')

@section('content')

<div class="dashboard-page">

    {{-- =========================================================
        VARIABLES
    ========================================================== --}}

    @php

        $meses = [
            1  => 'Enero',
            2  => 'Febrero',
            3  => 'Marzo',
            4  => 'Abril',
            5  => 'Mayo',
            6  => 'Junio',
            7  => 'Julio',
            8  => 'Agosto',
            9  => 'Septiembre',
            10 => 'Octubre',
            11 => 'Noviembre',
            12 => 'Diciembre',
        ];

        $currentYear = $year ?? now()->year;

        $selectedMonth = !empty($month)
            ? (int) $month
            : null;

        $mesSeleccionado = $selectedMonth
            ? ($meses[$selectedMonth] ?? 'Mes seleccionado')
            : 'Todo el año';

        $totalVentas = (float) ($stats['revenue'] ?? 0);

        $totalPedidos = (int) ($stats['orders'] ?? 0);

        $totalClientes = (int) ($stats['customers'] ?? 0);

        $totalProductos = (int) ($stats['products'] ?? 0);

        $ticketPromedio = $totalPedidos > 0
            ? $totalVentas / $totalPedidos
            : 0;

        $hayVentas = $totalPedidos > 0;

        $cantidadStockBajo = count($lowStock ?? []);

        /*
        |--------------------------------------------------------------------------
        | ACTIVIDAD RECIENTE
        |--------------------------------------------------------------------------
        | Ordenamos de la actividad más reciente a la más antigua
        | y mostramos únicamente los últimos 5 movimientos.
        */

        $recentActivities = collect($activities ?? [])
            ->sortByDesc(function ($activity) {
                return $activity->created_at ?? null;
            })
            ->take(5)
            ->values();

        /*
        |--------------------------------------------------------------------------
        | FECHA MÍNIMA PARA REPORTES
        |--------------------------------------------------------------------------
        |
        | Actualmente el sistema trabaja con información desde el año 2026.
        | Esto evita seleccionar fechas anteriores a dicho año.
        |
        */

        $reportMinimumDate = now()
            ->startOfYear()
            ->format('Y-m-d');

    @endphp


    {{-- =========================================================
        HEADER
    ========================================================== --}}

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


    {{-- =========================================================
        KPIs
    ========================================================== --}}

    <section class="dashboard-kpis">

        {{-- VENTAS --}}

        <div class="kpi-card">

            <div class="kpi-top">

                <span>
                    Ventas
                </span>

                <div class="kpi-icon red">

                    <i class="bi bi-cash-stack"></i>

                </div>

            </div>

            <h2>
                S/
                {{ number_format($totalVentas, 2) }}
            </h2>

            <p>
                Ingresos acumulados
            </p>

        </div>


        {{-- PEDIDOS --}}

        <div class="kpi-card">

            <div class="kpi-top">

                <span>
                    Pedidos
                </span>

                <div class="kpi-icon blue">

                    <i class="bi bi-cart-check-fill"></i>

                </div>

            </div>

            <h2>
                {{ number_format($totalPedidos) }}
            </h2>

            <p>
                Pedidos registrados
            </p>

        </div>


        {{-- CLIENTES --}}

        <div class="kpi-card">

            <div class="kpi-top">

                <span>
                    Clientes
                </span>

                <div class="kpi-icon gold">

                    <i class="bi bi-people-fill"></i>

                </div>

            </div>

            <h2>
                {{ number_format($totalClientes) }}
            </h2>

            <p>
                Clientes registrados
            </p>

        </div>


        {{-- PRODUCTOS --}}

        <div class="kpi-card">

            <div class="kpi-top">

                <span>
                    Productos
                </span>

                <div class="kpi-icon green">

                    <i class="bi bi-cup-hot-fill"></i>

                </div>

            </div>

            <h2>
                {{ number_format($totalProductos) }}
            </h2>

            <p>
                Productos disponibles
            </p>

        </div>

    </section>


    {{-- =========================================================
        GRÁFICO + REPORTES
    ========================================================== --}}

    <section class="dashboard-main">

        {{-- =====================================================
            GRÁFICO
        ====================================================== --}}

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

                            Mostrando ventas correspondientes a

                            <strong>

                                {{ $mesSeleccionado }}
                                {{ $currentYear }}

                            </strong>

                        </p>


                        @unless($hayVentas)

                            <div class="alert alert-warning mt-3 mb-0">

                                <i class="bi bi-exclamation-circle-fill me-2"></i>

                                No existen ventas para este período.

                            </div>

                        @endunless

                    </div>


                    {{-- FILTROS DEL GRÁFICO --}}

                    <form
                        method="GET"
                        class="card-actions"
                    >

                        <select
                            name="year"
                            class="form-select"
                        >

                            @foreach(
                                ($availableYears ?? [$currentYear])
                                as $availableYear
                            )

                                <option
                                    value="{{ $availableYear }}"
                                    @selected($currentYear == $availableYear)
                                >
                                    {{ $availableYear }}
                                </option>

                            @endforeach

                        </select>


                        <select
                            name="month"
                            class="form-select"
                        >

                            <option
                                value=""
                                @selected(empty($selectedMonth))
                            >
                                Todo el año
                            </option>


                            @foreach($meses as $numero => $nombre)

                                <option
                                    value="{{ $numero }}"
                                    @selected($selectedMonth === $numero)
                                >
                                    {{ $nombre }}
                                </option>

                            @endforeach

                        </select>


                        <button
                            type="submit"
                            class="btn btn-danger"
                        >

                            <i class="bi bi-funnel-fill me-2"></i>

                            Filtrar

                        </button>

                    </form>

                </div>


                {{-- GRÁFICO --}}

                <div class="chart-wrapper">

                    <canvas id="salesChart"></canvas>


                    @unless($hayVentas)

                        <div class="empty-chart-message">

                            <i class="bi bi-bar-chart-line"></i>

                            <h5>
                                No existen ventas
                            </h5>

                            <p>

                                No se registraron ventas para
                                {{ $mesSeleccionado }}
                                {{ $currentYear }}.

                            </p>

                        </div>

                    @endunless

                </div>

            </div>

        </div>


        {{-- =====================================================
            CENTRO DE REPORTES
        ====================================================== --}}

        <div class="dashboard-right">

            <div class="dashboard-card reports-card">

                <span class="section-badge">

                    <i class="bi bi-file-earmark-excel-fill"></i>

                    Centro de Reportes

                </span>


                <h3>
                    Exportar información
                </h3>


                <p>
                    Descarga reportes para analizar el rendimiento
                    de PROCÁFES.
                </p>


                <form
                    id="reportForm"
                    method="GET"
                >

                    {{-- =================================================
                        TIPO DE REPORTE
                    ================================================== --}}

                    <div class="mb-3">

                        <label
                            for="reportType"
                            class="form-label"
                        >
                            Reporte
                        </label>


                        <select
                            id="reportType"
                            name="report_type"
                            class="form-select"
                        >

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


                    {{-- =================================================
                        DESDE
                    ================================================== --}}

                    <div
                        id="fromGroup"
                        class="mb-3"
                    >

                        <label
                            for="reportFrom"
                            class="form-label"
                        >
                            Desde
                        </label>


                        <input
                            type="date"
                            id="reportFrom"
                            name="from"
                            class="form-control"
                            min="{{ $reportMinimumDate }}"
                            value="{{ request('from') }}"
                        >


                        <small class="text-muted d-block mt-1">
                            Desde {{ \Carbon\Carbon::parse($reportMinimumDate)->format('d/m/Y') }}
                        </small>

                    </div>


                    {{-- =================================================
                        HASTA
                    ================================================== --}}

                    <div
                        id="toGroup"
                        class="mb-4"
                    >

                        <label
                            for="reportTo"
                            class="form-label"
                        >
                            Hasta
                        </label>


                        <input
                            type="date"
                            id="reportTo"
                            name="to"
                            class="form-control"
                            min="{{ $reportMinimumDate }}"
                            value="{{ request('to') }}"
                        >

                    </div>


                    {{-- =================================================
                        INFORMACIÓN DEL REPORTE
                    ================================================== --}}

                    <div class="report-preview">

                        <h6>

                            <i class="bi bi-info-circle-fill text-primary me-2"></i>

                            Información del reporte

                        </h6>


                        <div class="preview-item">

                            <i class="bi bi-file-earmark-excel-fill text-success"></i>

                            <span>
                                Formato Excel (.xlsx)
                            </span>

                        </div>


                        <div class="preview-item">

                            <i class="bi bi-check-circle-fill text-success"></i>

                            <span>
                                Incluye encabezados automáticos
                            </span>

                        </div>


                        <div class="preview-item">

                            <i class="bi bi-table text-primary"></i>

                            <span>
                                Compatible con Excel y Google Sheets
                            </span>

                        </div>


                        <div class="preview-item">

                            <i class="bi bi-clock-history text-warning"></i>

                            <span>
                                Generación inmediata
                            </span>

                        </div>

                    </div>


                    {{-- =================================================
                        BOTÓN
                    ================================================== --}}

                    <button
                        type="submit"
                        class="btn btn-success w-100"
                    >

                        <i class="bi bi-download me-2"></i>

                        Generar Reporte Excel

                    </button>

                </form>

            </div>

        </div>

    </section>


    {{-- =========================================================
        PRODUCTOS + STOCK
    ========================================================== --}}

    <section class="dashboard-bottom">

        {{-- =====================================================
            PRODUCTOS MÁS VENDIDOS
        ====================================================== --}}

        <div class="dashboard-card">

            <div class="card-header-custom">

                <div>

                    <span class="section-badge">

                        <i class="bi bi-trophy-fill"></i>

                        Productos

                    </span>


                    <h3>
                        Productos más vendidos
                    </h3>


                    <p>
                        Productos con mayor cantidad de ventas.
                    </p>

                </div>


                <a
                    href="{{ route('admin.reports.best-sellers') }}"
                    class="kpi-link"
                >

                    Ver reporte

                    <i class="bi bi-arrow-right"></i>

                </a>

            </div>


            <div class="top-products-list">

                @forelse(($topProducts ?? []) as $product)

                    <div class="product-item">

                        @if(!empty($product->image))

                            <img
                                src="{{ asset($product->image) }}"
                                alt="{{ $product->name }}"
                            >

                        @else

                            <div class="product-placeholder">

                                <i class="bi bi-cup-hot-fill"></i>

                            </div>

                        @endif


                        <div class="flex-grow-1">

                            <strong>
                                {{ $product->name }}
                            </strong>


                            <small>

                                {{ number_format(
                                    $product->qty_sold
                                    ?? $product->quantity
                                    ?? 0
                                ) }}

                                unidades vendidas

                            </small>

                        </div>


                        <span class="badge">

                            {{ number_format(
                                $product->qty_sold
                                ?? $product->quantity
                                ?? 0
                            ) }}

                        </span>

                    </div>

                @empty

                    <div class="dashboard-empty">

                        <i class="bi bi-bar-chart"></i>

                        <h5>
                            Sin información de ventas
                        </h5>

                        <p>
                            Todavía no hay productos vendidos para mostrar.
                        </p>

                    </div>

                @endforelse

            </div>

        </div>


        {{-- =====================================================
            STOCK BAJO
        ====================================================== --}}

        <div class="dashboard-card">

            <div class="card-header-custom">

                <div>

                    <span class="section-badge">

                        <i class="bi bi-exclamation-triangle-fill"></i>

                        Inventario

                    </span>


                    <h3>
                        Stock bajo
                    </h3>


                    <p>
                        Productos que requieren reposición.
                    </p>

                </div>


                <a
                    href="{{ route('admin.reports.inventory') }}"
                    class="kpi-link"
                >

                    Ver inventario

                    <i class="bi bi-arrow-right"></i>

                </a>

            </div>


            <div class="stock-list">

                @forelse(($lowStock ?? []) as $product)

                    @php

                        $stock = (int) ($product->stock ?? 0);

                        $stockMinimo = (int) (
                            $product->stock_minimo ?? 10
                        );

                        $maxStock = max(
                            $stockMinimo,
                            10
                        );

                        $percentage = $maxStock > 0
                            ? min(
                                100,
                                ($stock / $maxStock) * 100
                            )
                            : 0;

                    @endphp


                    <div class="stock-item">

                        <div class="stock-info">

                            <strong>
                                {{ $product->name }}
                            </strong>


                            <span>

                                Stock actual:
                                {{ $stock }}
                                unidades

                            </span>


                            <div class="stock-progress">

                                <div
                                    style="width: {{ $percentage }}%;"
                                ></div>

                            </div>

                        </div>


                        <span class="stock-badge">

                            @if($stock <= 3)

                                Crítico

                            @elseif($stock <= 7)

                                Bajo

                            @else

                                Vigilar

                            @endif

                        </span>

                    </div>

                @empty

                    <div class="dashboard-empty">

                        <i class="bi bi-check-circle-fill text-success"></i>

                        <h5>
                            Inventario estable
                        </h5>

                        <p>
                            No existen productos con stock crítico.
                        </p>

                    </div>

                @endforelse

            </div>

        </div>

    </section>


    {{-- =========================================================
        ACTIVIDAD RECIENTE
    ========================================================== --}}

    <section class="dashboard-card dashboard-activity-card">

        <div class="card-header-custom activity-header">

            <div>

                <span class="section-badge">
                    <i class="bi bi-clock-history"></i>
                    Sistema
                </span>

                <h3>
                    Actividad reciente
                </h3>

                <p>
                    Últimos movimientos registrados en PROCÁFES.
                </p>

            </div>

            <a
                href="{{ route('admin.orders.index') }}"
                class="kpi-link"
            >
                Ver pedidos
                <i class="bi bi-arrow-right"></i>
            </a>

        </div>


        <div class="activity-list">

            @forelse($recentActivities as $activity)

                <div class="activity-item">

                    <div class="activity-icon">
                        <i class="bi bi-check-lg"></i>
                    </div>

                    <div class="activity-content">

                        <strong>
                            {{ $activity->title
                                ?? $activity->description
                                ?? 'Actividad registrada'
                            }}
                        </strong>

                        <small>

                            @if(!empty($activity->created_at))

                                {{ \Carbon\Carbon::parse(
                                    $activity->created_at
                                )->diffForHumans() }}

                            @else

                                Recientemente

                            @endif

                        </small>

                    </div>

                </div>

            @empty

                <div class="dashboard-empty">

                    <i class="bi bi-clock-history"></i>

                    <h5>
                        No hay actividad reciente
                    </h5>

                    <p>
                        Los últimos movimientos aparecerán aquí.
                    </p>

                </div>

            @endforelse

        </div>

    </section>


    {{-- =========================================================
        RESUMEN ADMINISTRATIVO
    ========================================================== --}}

    <section class="dashboard-summary-grid">

        {{-- PEDIDOS --}}

        <div class="summary-card">

            <div class="summary-icon blue">

                <i class="bi bi-cart-check"></i>

            </div>


            <div>

                <span>
                    Pedidos registrados
                </span>


                <strong>

                    {{ number_format(
                        $totalPedidos
                    ) }}

                </strong>

            </div>

        </div>


        {{-- CLIENTES --}}

        <div class="summary-card">

            <div class="summary-icon gold">

                <i class="bi bi-people"></i>

            </div>


            <div>

                <span>
                    Clientes registrados
                </span>


                <strong>

                    {{ number_format(
                        $totalClientes
                    ) }}

                </strong>

            </div>

        </div>


        {{-- STOCK BAJO --}}

        <div class="summary-card">

            <div class="summary-icon green">

                <i class="bi bi-box-seam"></i>

            </div>


            <div>

                <span>
                    Productos por revisar
                </span>


                <strong>

                    {{ number_format(
                        $cantidadStockBajo
                    ) }}

                </strong>

            </div>

        </div>

    </section>

</div>

@endsection


{{-- =============================================================
    JAVASCRIPT
============================================================= --}}

@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

document.addEventListener('DOMContentLoaded', function () {


    /* =========================================================
       GRÁFICO DE VENTAS
    ========================================================== */

    const canvas =
        document.getElementById('salesChart');


    if (canvas) {

        const ctx =
            canvas.getContext('2d');


        new Chart(ctx, {

            type: 'line',

            data: {

                labels:
                    @json($labels ?? []),

                datasets: [

                    {

                        label: 'Ventas',

                        data:
                            @json($revenue ?? []),

                        borderColor:
                            '#D62828',

                        backgroundColor:
                            'rgba(214,40,40,.10)',

                        fill: true,

                        tension: .40,

                        pointRadius: 5,

                        pointHoverRadius: 7,

                        pointBackgroundColor:
                            '#D62828',

                        pointBorderColor:
                            '#ffffff',

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

                        backgroundColor:
                            '#202020',

                        titleColor:
                            '#fff',

                        bodyColor:
                            '#fff',

                        padding: 12,

                        displayColors: false,


                        callbacks: {

                            label:
                                function(context) {

                                    return 'Ventas: S/ ' +

                                        Number(
                                            context.raw || 0
                                        ).toLocaleString(
                                            'es-PE',
                                            {
                                                minimumFractionDigits: 2,
                                                maximumFractionDigits: 2
                                            }
                                        );

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

                            color:
                                '#7c7c7c'

                        }

                    },


                    y: {

                        beginAtZero: true,

                        ticks: {

                            color:
                                '#7c7c7c',

                            callback:
                                function(value) {

                                    return 'S/ ' + value;

                                }

                        },

                        grid: {

                            color:
                                '#EFEFEF'

                        }

                    }

                }

            }

        });

    }


    /* =========================================================
       REPORTES EXCEL
    ========================================================== */

    const reportForm =
        document.getElementById('reportForm');

    const reportType =
        document.getElementById('reportType');

    const fromGroup =
        document.getElementById('fromGroup');

    const toGroup =
        document.getElementById('toGroup');

    const reportFrom =
        document.getElementById('reportFrom');

    const reportTo =
        document.getElementById('reportTo');


    /* =========================================================
       FECHA MÍNIMA
    ========================================================== */

    const minimumDate =
        "{{ $reportMinimumDate }}";


    /* =========================================================
       CONFIGURAR FECHAS
    ========================================================== */

    if (reportFrom) {

        reportFrom.min =
            minimumDate;

    }


    if (reportTo) {

        reportTo.min =
            minimumDate;

    }


    /* =========================================================
       REPORTES
    ========================================================== */

    const routes = {

        'sales':
            @json(route('admin.reports.sales')),

        'best-sellers':
            @json(route('admin.reports.best-sellers')),

        'least-sellers':
            @json(route('admin.reports.least-sellers')),

        'inventory':
            @json(route('admin.reports.inventory')),

        'categories':
            @json(route('admin.reports.categories')),

        'products':
            @json(route('admin.reports.products')),

        'orders':
            @json(route('admin.reports.orders'))

    };


    /* =========================================================
       REPORTES QUE NECESITAN FECHAS
    ========================================================== */

    const reportsWithDates = [

        'sales',

        'best-sellers',

        'least-sellers',

        'categories',

        'orders'

    ];


    /* =========================================================
       ACTUALIZAR FORMULARIO
    ========================================================== */

    function updateReportForm() {

        const selectedReport =
            reportType.value;


        /* =====================================================
           RUTA
        ====================================================== */

        if (routes[selectedReport]) {

            reportForm.action =
                routes[selectedReport];

        }


        /* =====================================================
           MOSTRAR / OCULTAR FECHAS
        ====================================================== */

        if (
            reportsWithDates.includes(
                selectedReport
            )
        ) {

            if (fromGroup) {

                fromGroup.style.display =
                    '';

            }


            if (toGroup) {

                toGroup.style.display =
                    '';

            }

        } else {

            if (fromGroup) {

                fromGroup.style.display =
                    'none';

            }


            if (toGroup) {

                toGroup.style.display =
                    'none';

            }


            /*
            |--------------------------------------------------------------------------
            | Limpiar fechas cuando el reporte
            | no necesita rango.
            |--------------------------------------------------------------------------
            */

            if (reportFrom) {

                reportFrom.value =
                    '';

            }


            if (reportTo) {

                reportTo.value =
                    '';

            }

        }

    }


    /* =========================================================
       CAMBIO DE "DESDE"
    ========================================================== */

    if (reportFrom && reportTo) {

        reportFrom.addEventListener(
            'change',
            function () {

                /*
                |--------------------------------------------------------------------------
                | Si no hay fecha Desde
                |--------------------------------------------------------------------------
                */

                if (!reportFrom.value) {

                    reportTo.min =
                        minimumDate;

                    return;

                }


                /*
                |--------------------------------------------------------------------------
                | Hasta no puede ser anterior a Desde
                |--------------------------------------------------------------------------
                */

                reportTo.min =
                    reportFrom.value;


                /*
                |--------------------------------------------------------------------------
                | Si Hasta quedó inválida,
                | la limpiamos.
                |--------------------------------------------------------------------------
                */

                if (
                    reportTo.value &&
                    reportTo.value <
                    reportFrom.value
                ) {

                    reportTo.value =
                        '';

                }

            }
        );

    }


    /* =========================================================
       SUBMIT DEL FORMULARIO
    ========================================================== */

    if (reportForm && reportType) {

        reportForm.addEventListener(
            'submit',
            function (event) {

                const selectedReport =
                    reportType.value;


                /* =================================================
                   VERIFICAR REPORTE
                ================================================== */

                if (!routes[selectedReport]) {

                    event.preventDefault();

                    alert(
                        'Seleccione un reporte válido.'
                    );

                    return;

                }


                /* =================================================
                   FORZAR RUTA
                ================================================== */

                reportForm.action =
                    routes[selectedReport];


                /* =================================================
                   REPORTES CON FECHAS
                ================================================== */

                if (
                    reportsWithDates.includes(
                        selectedReport
                    )
                ) {


                    /* =============================================
                       VALIDAR DESDE
                    ============================================== */

                    if (
                        reportFrom &&
                        reportFrom.value &&
                        reportFrom.value <
                        minimumDate
                    ) {

                        event.preventDefault();

                        alert(
                            'La fecha Desde no puede ser anterior al 01/01/2026.'
                        );

                        return;

                    }


                    /* =============================================
                       VALIDAR HASTA
                    ============================================== */

                    if (
                        reportTo &&
                        reportTo.value &&
                        reportTo.value <
                        minimumDate
                    ) {

                        event.preventDefault();

                        alert(
                            'La fecha Hasta no puede ser anterior al 01/01/2026.'
                        );

                        return;

                    }


                    /* =============================================
                       VALIDAR RANGO
                    ============================================== */

                    if (
                        reportFrom &&
                        reportTo &&
                        reportFrom.value &&
                        reportTo.value
                    ) {

                        if (
                            reportFrom.value >
                            reportTo.value
                        ) {

                            event.preventDefault();

                            alert(
                                'La fecha Desde no puede ser mayor que la fecha Hasta.'
                            );

                            return;

                        }

                    }

                }


                /* =================================================
                   DEBUG
                ================================================== */

                console.log(
                    'Reporte seleccionado:',
                    selectedReport
                );

                console.log(
                    'Desde:',
                    reportFrom
                        ? reportFrom.value
                        : null
                );

                console.log(
                    'Hasta:',
                    reportTo
                        ? reportTo.value
                        : null
                );

                console.log(
                    'Ruta:',
                    reportForm.action
                );

            }
        );

    }


    /* =========================================================
       INICIALIZAR
    ========================================================== */

    updateReportForm();


    /* =========================================================
       RESTAURAR REGLA DE "HASTA"
       SI YA EXISTE UN "DESDE"
    ========================================================== */

    if (
        reportFrom &&
        reportTo &&
        reportFrom.value
    ) {

        reportTo.min =
            reportFrom.value;

    }

});

</script>

@endpush