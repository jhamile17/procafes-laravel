{{--==========================================================================
    NAVBAR ACCOUNT
==========================================================================--}}

<div class="navbar-account">

    @guest

        <div class="navbar-auth">

            <a
                href="{{ route('login') }}"
                wire:navigate
                class="navbar-login">

                Iniciar sesión

            </a>

            <a
                href="{{ route('register') }}"
                wire:navigate
                class="navbar-register">

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
                            src="{{ asset('storage/' . $user->foto_perfil) }}"
                            alt="{{ $user->name }}">

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