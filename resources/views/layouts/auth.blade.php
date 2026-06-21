<!doctype html>
<html lang="es">
<head>
    <x-layout.head />
    @livewireStyles
</head>
<body class="bg-light">
    <main class="min-vh-100 d-flex align-items-center py-4">
        <div class="container">
            {{ $slot }}
        </div>
    </main>

    @livewireScripts
    @stack('scripts')
</body>
</html>