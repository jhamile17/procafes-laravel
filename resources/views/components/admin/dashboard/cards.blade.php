<div class="dashboard-cards">

    {{-- Productos --}}
    <div class="dashboard-card">

        <div>

            <span class="dashboard-card-title">

                Productos

            </span>

            <h2>

                {{ $productos }}

            </h2>

            <small>

                Total registrados

            </small>

            <a href="{{ route('admin.products.index') }}">

                Ver detalles →

            </a>

        </div>

        <div class="dashboard-icon bg-red">

            <i class="bi bi-cup-hot"></i>

        </div>

    </div>

    {{-- Clientes --}}

    <div class="dashboard-card">

        <div>

            <span class="dashboard-card-title">

                Clientes

            </span>

            <h2>

                {{ $clientes }}

            </h2>

            <small>

                Total registrados

            </small>

            <a href="{{ route('admin.users.index') }}">

                Ver detalles →

            </a>

        </div>

        <div class="dashboard-icon bg-yellow">

            <i class="bi bi-people"></i>

        </div>

    </div>

    {{-- Pedidos --}}

    <div class="dashboard-card">

        <div>

            <span class="dashboard-card-title">

                Pedidos

            </span>

            <h2>

                {{ $pedidos }}

            </h2>

            <small>

                Total realizados

            </small>

            <a href="{{ route('admin.orders.index') }}">

                Ver detalles →

            </a>

        </div>

        <div class="dashboard-icon bg-brown">

            <i class="bi bi-bag"></i>

        </div>

    </div>

    {{-- Ventas --}}

    <div class="dashboard-card">

        <div>

            <span class="dashboard-card-title">

                Ventas

            </span>

            <h2 class="text-success">

                S/
                {{ number_format($ventas,2) }}

            </h2>

            <small>

                Total vendido

            </small>

            <a href="#">

                Ver detalles →

            </a>

        </div>

        <div class="dashboard-icon bg-green">

            <i class="bi bi-cash-stack"></i>

        </div>

    </div>

</div>