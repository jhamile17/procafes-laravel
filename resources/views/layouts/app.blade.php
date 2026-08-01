<!DOCTYPE html>
<html lang="es">

<head>

    <x-layout.head />

    <meta name="csrf-token" content="{{ csrf_token() }}">

    @livewireStyles

    @stack('styles')

</head>

<body class="@yield('body-class')">

    {{-- ===========================
        NAVBAR
    ============================ --}}

    <x-navbar.navbar />

    {{-- ===========================
        CONTENIDO
    ============================ --}}

    <main id="app">

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

    <x-chat.button />

    <x-ecommerce.cart-offcanvas />

    <x-ui.alert />

    {{-- ===========================
        CONFIGURACIÓN JAVASCRIPT
    ============================ --}}

    <script>

        window.Laravel = {

            csrfToken: "{{ csrf_token() }}",

            routes: {

                cart: {

                    index: "{{ route('cart.index') }}",
                    data: "{{ route('cart.data') }}",
                    recommendations: "{{ route('cart.recommendations') }}",
                    add: "{{ route('cart.add') }}",
                    clear: "{{ route('cart.clear') }}",
                    base: "{{ url('/cart') }}"

                },

                wishlist: {

                    index: "{{ route('wishlist.index') }}",

                    toggle: "{{ route('wishlist.toggle') }}",

                    count: "{{ route('wishlist.count') }}",

                    clear: "{{ route('wishlist.clear') }}"

                },

                login: "{{ route('login') }}",

                checkout: "{{ route('checkout') }}"

            },

            auth: @json(auth()->check())

        };

    </script>

    {{-- ===========================
        SCRIPTS
    ============================ --}}

    @stack('scripts')

    @livewireScripts

</body>

</html>