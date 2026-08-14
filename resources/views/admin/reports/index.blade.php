@extends('layouts.admin')

@section('title', 'Reportes | PROCÁFES')

@section('content')

<div class="container-fluid">

    <div class="card shadow-sm border-0">

        <div class="card-header bg-white">

            <h3 class="mb-1">
                Centro de Reportes
            </h3>

            <p class="text-muted mb-0">
                Genera y descarga los reportes del sistema en formato Excel.
            </p>

        </div>

        <div class="card-body">

            <form
                id="reportForm"
                method="GET"
            >

                <div class="row">

                    <div class="col-md-4">

                        <label class="form-label">

                            Tipo de reporte

                        </label>

                        <select
                            class="form-select"
                            id="reportType"
                        >

                            <option value="sales">
                                Ventas
                            </option>

                            <option value="best-sellers">
                                Productos más vendidos
                            </option>

                            <option value="least-sellers">
                                Productos menos vendidos
                            </option>

                            <option value="inventory">
                                Inventario crítico
                            </option>

                            <option value="categories">
                                Ventas por categoría
                            </option>

                            <option value="products">
                                Inventario completo
                            </option>

                            <option value="orders">
                                Órdenes
                            </option>

                        </select>

                    </div>

                    <div
                        class="col-md-3"
                        id="fromGroup"
                    >

                        <label class="form-label">

                            Desde

                        </label>

                        <input
                            type="date"
                            class="form-control"
                            name="from"
                        >

                    </div>

                    <div
                        class="col-md-3"
                        id="toGroup"
                    >

                        <label class="form-label">

                            Hasta

                        </label>

                        <input
                            type="date"
                            class="form-control"
                            name="to"
                        >

                    </div>

                    <div
                        class="col-md-2 d-flex align-items-end"
                    >

                        <button
                            class="btn btn-danger w-100"
                            type="submit"
                        >

                            Generar

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection

@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    document.getElementById('reportForm').addEventListener('submit', function () {

        const report = document.getElementById('reportType').value;

        const routes = {
            sales: "{{ route('admin.reports.sales') }}",
            "best-sellers": "{{ route('admin.reports.best-sellers') }}",
            "least-sellers": "{{ route('admin.reports.least-sellers') }}",
            inventory: "{{ route('admin.reports.inventory') }}",
            categories: "{{ route('admin.reports.categories') }}",
            products: "{{ route('admin.reports.products') }}",
            orders: "{{ route('admin.reports.orders') }}"
        };

        this.action = routes[report];

    });

});

</script>

@endpush