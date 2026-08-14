<!DOCTYPE html>
<html lang="es">

<head>

    <x-layout.head />

    <meta name="csrf-token" content="{{ csrf_token() }}">

    @livewireStyles

    @stack('styles')

</head>

<body class="@yield('body-class') d-flex flex-column min-vh-100">
    {{-- ===========================
        NAVBAR
    ============================ --}}

    <x-navbar.navbar />

    {{-- ===========================
        CONTENIDO
    ============================ --}}

    <main id="app" class="flex-grow-1">

        @yield('content')

        {{ $slot ?? '' }}

    </main>

    {{-- ===========================
        FOOTER
    ============================ --}}

    <x-layout.footer />

    {{-- ===========================
        COMPONENTES GLOBALES
    ============================ --}}

    <x-ecommerce.whatsapp />

    <x-ecommerce.cart-offcanvas />

   <x-ui.alerts/>

    <x-ui.toast />
    {{-- ===========================
        CONFIGURACIÓN JAVASCRIPT
    ============================ --}}

    <script>

        window.Laravel = {

            csrfToken: "{{ csrf_token() }}",

            currentRoute: "{{ request()->route()?->getName() }}",

            auth: @json(auth()->check()),

            routes: {

                /*
                |--------------------------------------------------------------------------
                | Carrito
                |--------------------------------------------------------------------------
                */

                cart: {

                    index: "{{ route('cart.index') }}",

                    data: "{{ route('cart.data') }}",

                    recommendations: "{{ route('cart.recommendations') }}",

                    add: "{{ route('cart.add') }}",

                    clear: "{{ route('cart.clear') }}",

                    base: "{{ url('/cart') }}",

                },

                /*
                |--------------------------------------------------------------------------
                | Favoritos
                |--------------------------------------------------------------------------
                */

                wishlist: {

                    index: "{{ route('wishlist.index') }}",

                    toggle: "{{ route('wishlist.toggle') }}",

                    count: "{{ route('wishlist.count') }}",

                    clear: "{{ route('wishlist.clear') }}",

                },

                /*
                |--------------------------------------------------------------------------
                | Checkout
                |--------------------------------------------------------------------------
                */

                checkout: {

                    index: "{{ route('checkout.index') }}",

                    store: "{{ route('checkout.store') }}",

                },

                /*
                |--------------------------------------------------------------------------
                | Direcciones (LocationIQ)
                |--------------------------------------------------------------------------
                */

                address: {

                    search: "{{ route('customer.addresses.search') }}",
                    update: "{{ route('customer.addresses.update') }}",

                },

                /*
                |--------------------------------------------------------------------------
                | Autenticación
                |--------------------------------------------------------------------------
                */

                auth: {

                    login: "{{ route('login') }}",

                },
                documents:{

                consultarDni: "{{ route('customer.documentos.dni') }}",
                consultarRuc: "{{ route('customer.documentos.ruc') }}",
                 }
            }

        };

    </script>

    {{-- ===========================
        SCRIPTS
    ============================ --}}

    @stack('scripts')

    @livewireScripts

</body>

</html>