<nav class="navbar navbar-expand-lg">

    <div class="container">

        {{-- ===================== LOGO ===================== --}}
        <a
            href="{{ route('home') }}"
            class="navbar-brand">

            <img
                src="{{ asset('images/logo.jpg') }}"
                alt="PROCÁFES"
                class="navbar-logo">

            <span class="brand-title">
                PROCÁFES
            </span>

        </a>

        {{-- ===================== RESPONSIVE ===================== --}}
        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarContent"
            aria-controls="navbarContent"
            aria-expanded="false"
            aria-label="Abrir menú">

            <span class="navbar-toggler-icon"></span>

        </button>

        <div
            class="collapse navbar-collapse"
            id="navbarContent">

            {{-- ===================== MENÚ ===================== --}}
            <ul class="navbar-nav nav-menu mx-auto">

                <li class="nav-item">
                    <a
                        href="{{ route('home') }}"
                        class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                        Inicio
                    </a>
                </li>

                <li class="nav-item">
                    <a
                        href="{{ route('products') }}"
                        class="nav-link {{ request()->routeIs('products') ? 'active' : '' }}">
                        Productos
                    </a>
                </li>

                <li class="nav-item">
                    <a
                        href="{{ route('nosotros') }}"
                        class="nav-link {{ request()->routeIs('nosotros') ? 'active' : '' }}">
                        Nosotros
                    </a>
                </li>

                <li class="nav-item">
                    <a
                        href="{{ route('ubicanos') }}"
                        class="nav-link {{ request()->routeIs('ubicanos') ? 'active' : '' }}">
                        Ubícanos
                    </a>
                </li>

            </ul>

            {{-- ===================== BUSCADOR ===================== --}}
            <form
                action="{{ route('products') }}"
                method="GET"
                class="navbar-search">

                <div class="search-box">

                    <input
                        type="search"
                        name="search"
                        value="{{ request('search') }}"
                        class="form-control"
                        placeholder="Buscar productos...">

                    <button
                        type="submit"
                        class="search-btn"
                        aria-label="Buscar">

                        <i class="bi bi-search"></i>

                    </button>

                </div>

            </form>

            {{-- ===================== ACCIONES ===================== --}}
            <div class="nav-actions">

                {{-- Favoritos --}}
                <button
                    type="button"
                    class="btn btn-icon position-relative"
                    onclick="window.location='{{ route('wishlist.index') }}'"
                    aria-label="Favoritos">

                    <i class="bi bi-heart"></i>

                    <span
                        id="wishlistBadge"
                        class="wishlist-badge">
                        0
                    </span>

                    <span
                        id="wishlistMessage"
                        class="wishlist-message"></span>

                </button>

                {{-- Carrito --}}
                <x-ecommerce.cart-button />

                @auth

                    {{-- Usuario --}}
                    <div class="dropdown">

                        <button
                            class="btn btn-user"
                            type="button"
                            data-bs-toggle="dropdown"
                            aria-expanded="false">

                            <i class="bi bi-person-circle"></i>

                            <span class="nav-user-name">
                                {{ explode(' ', auth()->user()->nombres)[0] }}
                            </span>

                            <i class="bi bi-chevron-down small"></i>

                        </button>

                        <ul class="dropdown-menu dropdown-menu-end">

                            <li>
                                <a
                                    class="dropdown-item"
                                    href="{{ route('customer.dashboard') }}">
                                    <i class="bi bi-speedometer2 me-2"></i>
                                    Mi panel
                                </a>
                            </li>

                            <li>
                                <a
                                    class="dropdown-item"
                                    href="{{ route('profile') }}">
                                    <i class="bi bi-person me-2"></i>
                                    Perfil
                                </a>
                            </li>

                            <li>
                                <hr class="dropdown-divider">
                            </li>

                            <li>

                                <form
                                    action="{{ route('logout') }}"
                                    method="POST">

                                    @csrf

                                    <button
                                        type="submit"
                                        class="dropdown-item">

                                        <i class="bi bi-box-arrow-right me-2"></i>

                                        Cerrar sesión

                                    </button>

                                </form>

                            </li>

                        </ul>

                    </div>

                @else

                    {{-- Invitado --}}
                    <a
                        href="{{ route('login') }}"
                        class="btn btn-login">

                        Iniciar sesión

                    </a>

                    <a
                        href="{{ route('register') }}"
                        class="btn btn-register">

                        Registrarse

                    </a>

                @endauth

            </div>

        </div>

    </div>

</nav>