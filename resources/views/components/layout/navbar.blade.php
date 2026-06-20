<nav class="navbar navbar-expand-lg bg-white shadow-sm sticky-top border-bottom">
    <div class="container">

        {{-- Logo --}}
        <a class="navbar-brand fw-bold" href="{{ route('home') }}">
            PROCAFES
        </a>

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
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active fw-bold' : '' }}"
                       href="{{ route('home') }}">
                        Inicio
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('products') ? 'active fw-bold' : '' }}"
                       href="{{ route('products') }}">
                        Productos
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('nosotros') ? 'active fw-bold' : '' }}"
                       href="{{ route('nosotros') }}">
                        Nosotros
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('ubicanos') ? 'active fw-bold' : '' }}"
                       href="{{ route('ubicanos') }}">
                        Ubícanos
                    </a>
                </li>
            </ul>

            {{-- Buscador --}}
            <form class="d-flex mx-lg-3 my-3 my-lg-0" action="{{ route('products') }}" method="GET">
                <input
                    class="form-control me-2"
                    type="search"
                    name="search"
                    placeholder="Buscar café..."
                    aria-label="Buscar">

                <button class="btn btn-outline-dark" type="submit">
                    <i class="bi bi-search"></i>
                </button>
            </form>

            {{-- Carrito --}}
            <div class="me-3">
                <x-ecommerce.cart-button />
            </div>

            {{-- Usuario --}}
           @auth

<div class="dropdown">

    <button
        class="btn btn-outline-dark dropdown-toggle"
        data-bs-toggle="dropdown">

        {{ Auth::user()->name }}
    </button>

    <ul class="dropdown-menu dropdown-menu-end">

        <li>
            <a class="dropdown-item"
               href="{{ route('customer.dashboard') }}">
                Mi panel
            </a>
        </li>

        <li>
            <a class="dropdown-item"
               href="{{ route('profile') }}">
                Perfil
            </a>
        </li>

        <li><hr class="dropdown-divider"></li>

        <li>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="dropdown-item">
                    Cerrar sesión
                </button>
            </form>
        </li>

    </ul>

</div>

@else

<div class="d-flex gap-2">

    <a href="{{ route('login') }}"
       class="btn btn-outline-dark">
        Iniciar sesión
    </a>

    @if(Route::has('register'))
        <a href="{{ route('register') }}"
           class="btn btn-dark">
            Registrarse
        </a>
    @endif

</div>

@endauth

        </div>

    </div>
</nav>