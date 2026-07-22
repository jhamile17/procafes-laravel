{{--==========================================================================
    NAVBAR BRAND
    --------------------------------------------------------------------------
    Muestra el logotipo y el nombre comercial de PROCÁFES.
==========================================================================--}}

<a
    href="{{ route('home') }}"
    class="navbar-brand"
    aria-label="Ir a la página de inicio">

    {{-- Logo --}}
    <img
        src="{{ asset('images/logo.jpg') }}"
        alt="Logo de PROCÁFES"
        class="navbar-brand-logo">

    {{-- Nombre de la empresa --}}
    <span class="navbar-brand-text">

        PROCÁFES

    </span>

</a>