{{--==========================================================================
    NAVBAR BRAND
==========================================================================--}}

<a
    href="{{ route('home') }}"
    class="navbar-brand"
    wire:navigate
    aria-label="Ir a la página principal de PROCÁFES">

    <img
        src="{{ asset('images/logo.jpg') }}"
        alt="Logo de PROCÁFES"
        class="navbar-brand-logo">

    <span class="navbar-brand-text">

        PROCÁFES

    </span>

</a>