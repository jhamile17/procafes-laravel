<nav class="navbar navbar-expand-lg">

    <div class="container">

        {{-- Logo --}}
        <a class="navbar-brand d-flex align-items-center"
           href="{{ route('home') }}">

            <img
                src="{{ asset('images/logo.png') }}"
                alt="Procafes"
                class="navbar-logo">

            <div>

                <div class="brand-title">
                    PROCAFES
                </div>

                <div class="brand-subtitle">
                    Café de especialidad
                </div>

            </div>

        </a>

        {{-- Botón Responsive --}}
        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#nav"
            aria-controls="nav"
            aria-expanded="false"
            aria-label="Toggle navigation">

            <span class="navbar-toggler-icon"></span>

        </button>

        <div class="collapse navbar-collapse" id="nav">

            {{-- Menú --}}
            <ul class="navbar-nav mx-auto">

                <li class="nav-item">

                    <a
                        href="{{ route('home') }}"
                        class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">

                        <i class="bi bi-house-door"></i>

                        Inicio

                    </a>

                </li>

                <li class="nav-item">

                    <a
                        href="{{ route('products') }}"
                        class="nav-link {{ request()->routeIs('products') ? 'active' : '' }}">

                        <i class="bi bi-cup-hot"></i>

                        Productos

                    </a>

                </li>

                <li class="nav-item">

                    <a
                        href="{{ route('nosotros') }}"
                        class="nav-link {{ request()->routeIs('nosotros') ? 'active' : '' }}">

                        <i class="bi bi-people"></i>

                        Nosotros

                    </a>

                </li>

                <li class="nav-item">

                    <a
                        href="{{ route('ubicanos') }}"
                        class="nav-link {{ request()->routeIs('ubicanos') ? 'active' : '' }}">

                        <i class="bi bi-geo-alt"></i>

                        Ubícanos

                    </a>

                </li>

            </ul>

            {{-- Buscador --}}
            <form
                class="navbar-search"
                action="{{ route('products') }}"
                method="GET">

                <div class="position-relative">

                    <i class="bi bi-search navbar-search-icon"></i>

                    <input
                        type="search"
                        name="search"
                        class="form-control"
                        value="{{ request('search') }}"
                        placeholder="Buscar productos...">

                </div>

            </form>

            {{-- Acciones --}}
            <div class="nav-actions">

                {{-- Carrito --}}
                <x-ecommerce.cart-button />

                @auth

                    <div class="dropdown">

                        <button
                            class="btn btn-outline-primary dropdown-toggle"
                            data-bs-toggle="dropdown">

                            <i class="bi bi-person-circle me-2"></i>

                            {{ Auth::user()->name }}

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
                                    method="POST"
                                    action="{{ route('logout') }}">

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

                    <a
                        href="{{ route('login') }}"
                        class="btn btn-outline-primary">

                        Iniciar sesión

                    </a>

                    @if(Route::has('register'))

                        <a
                            href="{{ route('register') }}"
                            class="btn btn-primary">

                            Registrarse

                        </a>

                    @endif

                @endauth

            </div>

        </div>

    </div>

</nav>