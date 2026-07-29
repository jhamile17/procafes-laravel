<!DOCTYPE html>
<html lang="es">

<head>

    <x-layout.head />

    @livewireStyles

    @stack('styles')

</head>

<body class="auth-layout">
    <x-navbar.navbar />
    {{ $slot }}
    @livewireScripts
    <script src="{{ asset('js/auth/auth.js') }}"></script>
    @stack('scripts')
</body>
</html>