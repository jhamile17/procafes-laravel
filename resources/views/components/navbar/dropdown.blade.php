{{--==========================================================================
    NAVBAR DROPDOWN
    --------------------------------------------------------------------------
    Menú desplegable del usuario autenticado.
==========================================================================--}}

<ul class="dropdown-menu dropdown-menu-end navbar-dropdown">

    <li>
        <a
            href="{{ route('home') }}"
            class="dropdown-item">

            <i class="bi bi-person" aria-hidden="true"></i>

            <span>Mi perfil</span>

        </a>
    </li>

    <li>
        <a
            href="{{ route('home') }}"
            class="dropdown-item">

            <i class="bi bi-bag" aria-hidden="true"></i>

            <span>Mis pedidos</span>

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

                <i
                    class="bi bi-box-arrow-right"
                    aria-hidden="true">
                </i>

                <span>Cerrar sesión</span>

            </button>

        </form>

    </li>

</ul>