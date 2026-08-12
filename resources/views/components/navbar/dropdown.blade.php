<ul class="dropdown-menu dropdown-menu-end navbar-dropdown">

    <li>
        <a
            href="{{ route('customer.profile') }}"
            class="navbar-dropdown-item"
        >
            <span class="navbar-dropdown-icon">
                <i class="bi bi-person" aria-hidden="true"></i>
            </span>

            <span class="navbar-dropdown-text">
                Mi perfil
            </span>
        </a>
    </li>

    <li>
        <a
            href="{{ route('customer.orders') }}"
            class="navbar-dropdown-item"
        >
            <span class="navbar-dropdown-icon">
                <i class="bi bi-bag" aria-hidden="true"></i>
            </span>

            <span class="navbar-dropdown-text">
                Mis pedidos
            </span>
        </a>
    </li>

    <li class="navbar-dropdown-divider"></li>

    <li>
        <form
            action="{{ route('logout') }}"
            method="POST"
        >
            @csrf

            <button
                type="submit"
                class="navbar-dropdown-item navbar-dropdown-logout"
            >
                <span class="navbar-dropdown-icon">
                    <i
                        class="bi bi-box-arrow-right"
                        aria-hidden="true"
                    ></i>
                </span>

                <span class="navbar-dropdown-text">
                    Cerrar sesión
                </span>
            </button>
        </form>
    </li>

</ul>