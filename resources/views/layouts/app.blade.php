<!doctype html>
<html lang="es">
<head>
    <x-layout.head />
    @livewireStyles
</head>
<body>
    <x-layout.navbar />

    <main>
        @yield('content')
        {{ $slot ?? '' }}
    </main>

    <x-layout.footer />

    <x-ecommerce.whatsapp />
    <x-chat.button />
    <x-chat.modal />

    <x-ecommerce.cart-offcanvas />

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
            }
        };
    </script>

    @<script src="{{ asset('vendor/livewire/livewire.min.js') }}"></script>
    @stack('scripts')
</body>
</html>