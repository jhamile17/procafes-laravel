{{--==========================================================================
    NAVBAR NAVIGATION
==========================================================================--}}

<ul class="navbar-nav navbar-menu">

    <li class="nav-item">

        <a
            href="{{ route('home') }}"
            wire:navigate
            class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">

            Inicio

        </a>

    </li>

    <li class="nav-item">

        <a
            href="{{ route('products') }}"
            wire:navigate
            class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">

            Productos

        </a>

    </li>

    <li class="nav-item">

        <a
            href="{{ route('nosotros') }}"
            wire:navigate
            class="nav-link {{ request()->routeIs('nosotros') ? 'active' : '' }}">

            Nosotros

        </a>

    </li>

    <li class="nav-item">

        <a
            href="{{ route('ubicanos') }}"
            wire:navigate
            class="nav-link {{ request()->routeIs('ubicanos') ? 'active' : '' }}">

            Ubícanos

        </a>

    </li>

</ul>