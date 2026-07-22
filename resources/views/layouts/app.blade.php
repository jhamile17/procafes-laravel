<!doctype html>
<html lang="es">
<head>
    <x-layout.head />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @livewireStyles
</head>
<body>

    <x-navbar.navbar/>

    <main>
        @yield('content')
        {{ $slot ?? '' }}
    </main>

    <x-layout.footer />

    <x-ecommerce.whatsapp />

    <x-chat.button />

    <x-ecommerce.cart-offcanvas />
    <div class="toast-container position-fixed bottom-0 end-0 p-3">

    <div
        id="appToast"
        class="toast border-0 shadow"
        role="alert"
        aria-live="assertive"
        aria-atomic="true">

        <div class="toast-body fw-medium"></div>

    </div>

</div>
    <script>

        window.Laravel = {

            csrfToken: '{{ csrf_token() }}',

            routes: {

                index: '{{ route('cart.index') }}',

                add: '{{ route('cart.add') }}',

                base: '{{ url('/cart') }}',

                clear: '{{ route('cart.clear') }}',

            }

        };

        window.App = {

            isAuth: @json(auth()->check()),

            routes: {

                login: '{{ route('login') }}',

                checkout: '{{ route('checkout') }}',
                wishlist: {
                     toggle: '{{ route('wishlist.toggle') }}',
                    sync: '{{ route('wishlist.sync') }}',
                }

            }

        };

    </script>

    @stack('scripts')

</body>
</html>