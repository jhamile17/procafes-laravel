<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>
        @yield('title', 'Panel Administrativo | PROCÁFES')
    </title>

    {{-- Bootstrap --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
        rel="stylesheet">

    {{-- Google Fonts --}}
    <link
        rel="preconnect"
        href="https://fonts.googleapis.com">

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap"
        rel="stylesheet">

    {{-- Vite --}}
    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

    @stack('styles')

</head>

<body>

<div class="admin-layout">

    {{-- ===========================================================
        SIDEBAR
    ============================================================ --}}

    <aside class="sidebar">

        <div class="sidebar-brand">

            <img
                src="{{ asset('images/logo.jpg') }}"
                class="brand-logo"
                alt="PROCÁFES">

            <h2>PROCÁFES</h2>

            <span>
                Panel Administrativo
            </span>

        </div>


        <nav class="sidebar-menu">

            <span class="sidebar-section">
                GENERAL
            </span>


            <a
                href="{{ route('admin.dashboard') }}"
                class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">

                <i class="bi bi-speedometer2"></i>

                <span>
                    Dashboard
                </span>

            </a>


            <a
                href="{{ route('admin.products.index') }}"
                class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}">

                <i class="bi bi-cup-hot-fill"></i>

                <span>
                    Productos
                </span>

            </a>


            <a
                href="{{ route('admin.categories.index') }}"
                class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">

                <i class="bi bi-grid-fill"></i>

                <span>
                    Categorías
                </span>

            </a>


            <a
                href="{{ route('admin.brands.index') }}"
                class="{{ request()->routeIs('admin.brands.*') ? 'active' : '' }}">

                <i class="bi bi-award-fill"></i>

                <span>
                    Marcas
                </span>

            </a>


            <span class="sidebar-section">
                VENTAS
            </span>


            <a
                href="{{ route('admin.orders.index') }}"
                class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">

                <i class="bi bi-cart-check-fill"></i>

                <span>
                    Pedidos
                </span>

            </a>


            <a
                href="{{ route('admin.billing.index') }}"
                class="{{ request()->routeIs('admin.billing.*') ? 'active' : '' }}">

                <i class="bi bi-receipt-cutoff"></i>

                <span>
                    Facturación
                </span>

            </a>


            <span class="sidebar-section">
                SISTEMA
            </span>


            <a
                href="{{ route('admin.users.index') }}"
                class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">

                <i class="bi bi-people-fill"></i>

                <span>
                    Usuarios
                </span>

            </a>


            <a
                href="{{ route('admin.configuracion.index') }}"
                class="{{ request()->routeIs('admin.configuracion.*') ? 'active' : '' }}">

                <i class="bi bi-gear-fill"></i>

                <span>
                    Configuración
                </span>

            </a>

        </nav>


        {{-- =======================================================
            LOGOUT
        ======================================================== --}}

        <div class="sidebar-footer">

            <form
                action="{{ route('logout') }}"
                method="POST">

                @csrf

                <button
                    type="submit"
                    class="logout-button">

                    <i class="bi bi-box-arrow-right"></i>

                    <span>
                        Cerrar sesión
                    </span>

                </button>

            </form>

        </div>

    </aside>


    {{-- ===========================================================
        CONTENIDO PRINCIPAL
    ============================================================ --}}

    <main class="main-content">


        {{-- =======================================================
            TOPBAR
        ======================================================== --}}

        <header class="topbar">

            {{-- IZQUIERDA VACÍA
                 El título de cada página se muestra dentro
                 de su propia vista.
            --}}

            <div class="topbar-left"></div>


            {{-- DERECHA --}}

            <div class="topbar-right">


                {{-- VER TIENDA --}}

                <a
                    href="{{ url('/') }}"
                    target="_blank"
                    class="btn-admin">

                    <i class="bi bi-shop-window"></i>

                    <span>
                        Ver tienda
                    </span>

                </a>


                {{-- PERFIL --}}

                <div class="top-profile">

                    <div class="top-avatar">

                        @if(auth()->user()->foto_perfil)

                            <img
                                src="{{ asset(auth()->user()->foto_perfil) }}"
                                alt="Administrador">

                        @else

                            <i class="bi bi-person-fill"></i>

                        @endif

                    </div>


                    <div class="top-profile-info">

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


        {{-- =======================================================
            CONTENIDO
        ======================================================== --}}

        <section class="dashboard-container">

            @yield('content')

        </section>


        {{-- =======================================================
            FOOTER
        ======================================================== --}}

        <footer class="admin-footer">

            <div>

                © {{ date('Y') }}

                <strong>
                    PROCÁFES
                </strong>

            </div>

            <div>

                Sistema Administrativo · v1.0

            </div>

        </footer>

    </main>

</div>


{{-- Bootstrap JS --}}

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>


@stack('scripts')

</body>

</html>