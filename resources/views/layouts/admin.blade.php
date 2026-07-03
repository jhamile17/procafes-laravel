<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        @yield('title', 'Panel Administrativo | PROCÁFES')
    </title>

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- CSS del administrador --}}
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

    @stack('styles')

</head>

<body>

<div class="admin-layout">

    {{-- =========================
        SIDEBAR
    ========================== --}}

    <aside class="sidebar">

        <div class="sidebar-logo">

            <img src="{{ asset('images/logo.png') }}" alt="PROCÁFES">

            <div>

                <h4>PROCÁFES</h4>

                <span>Administrador</span>

            </div>

        </div>

        <nav class="sidebar-menu">

            <a href="{{ route('admin.dashboard') }}"
               class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">

                <i class="bi bi-grid"></i>

                Dashboard

            </a>

            <a href="{{ route('admin.products.index') }}"
               class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}">

                <i class="bi bi-box-seam"></i>

                Productos

            </a>

            <a href="{{ route('admin.categories.index') }}"
               class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">

                <i class="bi bi-tags"></i>

                Categorías

            </a>

            <a href="{{ route('admin.brands.index') }}"
               class="{{ request()->routeIs('admin.brands.*') ? 'active' : '' }}">

                <i class="bi bi-award"></i>

                Marcas

            </a>

            <a href="{{ route('admin.users.index') }}"
               class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">

                <i class="bi bi-people"></i>

                Usuarios

            </a>

            <a href="{{ route('admin.orders.index') }}"
               class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">

                <i class="bi bi-cart-check"></i>

                Pedidos

            </a>

            <a href="{{ route('admin.billing.index') }}"
               class="{{ request()->routeIs('admin.billing.*') ? 'active' : '' }}">

                <i class="bi bi-receipt"></i>

                Facturación

            </a>

        </nav>

    </aside>

    {{-- =========================
        CONTENIDO
    ========================== --}}

    <main class="main-content">

        {{-- ======================================
    TOPBAR
======================================= --}}

<header class="topbar">

    <div class="topbar-left">

        <div>

            <h2 class="page-title">
                @yield('title', 'Dashboard')
            </h2>

            <p class="page-subtitle">
                Bienvenido nuevamente,
                <strong>{{ auth()->user()->nombre_completo ?? auth()->user()->name }}</strong>
            </p>

        </div>

    </div>

    <div class="topbar-right">

        <button class="icon-button">

            <i class="bi bi-bell"></i>

        </button>

        <button class="icon-button">

            <i class="bi bi-envelope"></i>

        </button>

        <div class="admin-profile">

            <img
                src="{{ asset('images/avatar_admin.png') }}"
                alt="Administrador">

            <div>

                <strong>

                    {{ auth()->user()->nombre_completo ?? auth()->user()->name }}

                </strong>

                <small>

                    Administrador

                </small>

            </div>

        </div>

    </div>

</header>

{{-- ======================================
    CONTENIDO
======================================= --}}

<section class="dashboard-container">

    @yield('content')

</section>

{{-- ======================================
    FOOTER
======================================= --}}

<footer class="admin-footer">

    © {{ date('Y') }}

    <strong>PROCÁFES</strong>

    Todos los derechos reservados.

</footer>

</main>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

@stack('scripts')

</body>

</html>