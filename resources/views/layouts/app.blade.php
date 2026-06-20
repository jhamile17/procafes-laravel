<!doctype html>
<html lang="es">

<head>
    <x-layout.head />
</head>

<body>

    <x-layout.navbar />

    <main>
        @yield('content')
    </main>

    <x-layout.footer />

    {{-- WhatsApp flotante --}}
    <x-ecommerce.whatsapp />

    {{-- IA flotante --}}
    <x-chat.button />

    {{-- Modal IA --}}
    <x-chat.modal />

</body>

</html>