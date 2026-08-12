<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>

        @yield('title','Panel Administrativo | PROCÁFES')

    </title>

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
        rel="stylesheet">

    {{-- Google Fonts --}}
    <link rel="preconnect"
        href="https://fonts.googleapis.com">

    <link rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    {{-- CSS --}}
    <link rel="stylesheet"
        href="{{ asset('css/admin.css') }}">

    @stack('styles')

</head>

<body>

<div class="admin-layout">

    {{-- =====================================================
        SIDEBAR
    ====================================================== --}}

    <aside class="sidebar">

        {{-- =============================
            LOGO
        ============================== --}}

        <div class="sidebar-brand">

            <img
                src="{{ asset('images/logo.jpg') }}"
                alt="PROCÁFES"
                class="brand-logo">

            <h2>

                PROCÁFES

            </h2>

            <span>

                Pasión por el buen café

            </span>

        </div>

        <div class="sidebar-separator"></div>

        {{-- =============================
            PERFIL
        ============================== --}}

        <div class="sidebar-profile">

            <div class="profile-avatar">

                @if(auth()->user()->foto_perfil)

                    <img
                        src="{{ asset(auth()->user()->foto_perfil) }}"
                        alt="Administrador">

                @else

                    <i class="bi bi-person-fill"></i>

                @endif

            </div>

            <div class="profile-content">

                <h5>

                    {{ auth()->user()->nombre_completo ?? auth()->user()->name }}

                </h5>

                <p>

                    {{ auth()->user()->email }}

                </p>

                <span>

                    Administrador

                </span>

            </div>

        </div>

        <div class="sidebar-separator"></div>

        {{-- =============================
            MENÚ
        ============================== --}}

        <nav class="sidebar-menu">

            <a href="{{ route('admin.dashboard') }}"
                class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">

                <i class="bi bi-house-door-fill"></i>

                <span>Dashboard</span>

            </a>

            <a href="{{ route('admin.products.index') }}"
                class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}">

                <i class="bi bi-cup-hot-fill"></i>

                <span>Productos</span>

            </a>

            <a href="{{ route('admin.categories.index') }}"
                class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">

                <i class="bi bi-grid-fill"></i>

                <span>Categorías</span>

            </a>

            <a href="{{ route('admin.brands.index') }}"
                class="{{ request()->routeIs('admin.brands.*') ? 'active' : '' }}">

                <i class="bi bi-award-fill"></i>

                <span>Marcas</span>

            </a>

            <a href="{{ route('admin.users.index') }}"
                class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">

                <i class="bi bi-people-fill"></i>

                <span>Usuarios</span>

            </a>

            <a href="{{ route('admin.orders.index') }}"
                class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">

                <i class="bi bi-cart-check-fill"></i>

                <span>Pedidos</span>

            </a>

            <a href="{{ route('admin.billing.index') }}"
                class="{{ request()->routeIs('admin.billing.*') ? 'active' : '' }}">

                <i class="bi bi-receipt-cutoff"></i>

                <span>Facturación</span>

            </a>

            <a href="#">

                <i class="bi bi-bar-chart-fill"></i>

                <span>Reportes</span>

            </a>

        </nav>

        <div class="sidebar-footer">

            <form
                action="{{ route('logout') }}"
                method="POST">

                @csrf

                <button
                    class="logout-button"
                    type="submit">

                    <i class="bi bi-box-arrow-right"></i>

                    <span>

                        Cerrar sesión

                    </span>

                </button>

            </form>

            <div class="coffee-image">

                <img
                    src="{{ asset('images/sidebar-coffee.png') }}"
                    alt="Coffee">

            </div>

        </div>

    </aside>

    {{-- =====================================================
        CONTENIDO PRINCIPAL
    ====================================================== --}}

    <main class="main-content">
            {{-- =====================================================
        HEADER
    ====================================================== --}}

    <header class="topbar">

        <div class="topbar-right">

            <div class="topbar-right">

                <a href="{{ url('/') }}" target="_blank" class="btn-admin">
                    <i class="bi bi-shop-window"></i>
                    <span>Ver tienda</span>
                </a>

                <div class="top-profile">

                    <div class="top-avatar">
                        <i class="bi bi-person-fill"></i>
                    </div>

                    <div>
                        <strong>{{ auth()->user()->name }}</strong>
                        <small>Administrador</small>
                    </div>

                </div>
                
            </div>

        </div>

    </header>

    {{-- =====================================================
        CONTENIDO
    ====================================================== --}}

    <section class="dashboard-container">

        @yield('content')

    </section>

    {{-- =====================================================
        FOOTER
    ====================================================== --}}

    <footer class="admin-footer">

        <div class="footer-left">

            © {{ date('Y') }}

            <strong>

                PROCÁFES

            </strong>

            · Todos los derechos reservados.

        </div>

        <div class="footer-right">

            <span>

                Sistema Administrativo

            </span>

            <span class="version">

                v1.0

            </span>

        </div>

    </footer>

    </main>

</div>

{{-- Bootstrap --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

@stack('scripts')

</body>

</html>