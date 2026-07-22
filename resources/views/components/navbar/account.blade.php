{{--==========================================================================
    NAVBAR ACCOUNT
    --------------------------------------------------------------------------
    Muestra las acciones de autenticación del usuario.
==========================================================================--}}

<div class="navbar-account">

    @guest

        <div class="navbar-auth">

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

        </div>

    @else

        @php($user = Auth::user())

        <div class="dropdown">

            <button
                type="button"
                class="navbar-user dropdown-toggle"
                data-bs-toggle="dropdown"
                aria-expanded="false"
                aria-label="Menú del usuario">

                <span class="navbar-user-avatar">

                    @if($user->foto_perfil)

                        <img
                            src="{{ asset($user->foto_perfil) }}"
                            alt="Foto de {{ $user->name }}">

                    @else

                        <i
                            class="bi bi-person-circle"
                            aria-hidden="true">
                        </i>

                    @endif

                </span>

                <span class="navbar-user-name">

                    {{ $user->name }}

                </span>

            </button>

            <x-navbar.dropdown />

        </div>

    @endguest

</div>