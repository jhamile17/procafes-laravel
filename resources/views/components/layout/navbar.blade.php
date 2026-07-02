<nav class="navbar navbar-expand-lg">

    <div class="container">

        {{-- Logo --}}
        <a href="{{ route('home') }}" class="navbar-brand">

            <img
                src="{{ asset('images/logo.jpg') }}"
                alt="Procafes"
                class="navbar-logo">

            <span class="brand-title">
                PROCAFES
            </span>

        </a>

        {{-- Botón responsive --}}
        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarContent"
            aria-controls="navbarContent"
            aria-expanded="false"
            aria-label="Toggle navigation">

            <span class="navbar-toggler-icon"></span>

        </button>

        <div
            class="collapse navbar-collapse"
            id="navbarContent">

            {{-- Menú --}}
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

            {{-- Buscador --}}
            <form
                action="{{ route('products') }}"
                method="GET"
                class="navbar-search">

                <div class="search-box">

                    <input
                        type="search"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Buscar productos..."
                        class="form-control">

                    <button
                        type="submit"
                        class="search-btn">

                        <i class="bi bi-search"></i>

                    </button>

                </div>

            </form>

            {{-- Acciones --}}
            <div class="nav-actions">

                {{-- Favoritos --}}
                <a
                    href="{{ route('wishlist.index') }}"
                    class="nav-icon"
                    title="Favoritos">

                    <i class="bi bi-heart"></i>

                </a>

                {{-- Carrito --}}
                <x-ecommerce.cart-button />

                @auth

                    <div class="dropdown">

                        <button
                            class="nav-user"
                            data-bs-toggle="dropdown">

                            <i class="bi bi-person"></i>

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
                                        class="dropdown-item"
                                        type="submit">

                                        <i class="bi bi-box-arrow-right me-2"></i>

                                        Cerrar sesión

                                    </button>

                                </form>

                            </li>

                        </ul>

                    </div>

                @else

                    <a
                        href="{{ route('login') }}"
                        class="btn btn-outline-primary btn-auth">

                        Iniciar sesión

                    </a>

                    <a
                        href="{{ route('register') }}"
                        class="btn btn-primary btn-auth">

                        Registrarse

                    </a>

                @endauth

            </div>

        </div>

    </div>

</nav>