<meta charset="utf-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1"
>

<meta
    name="csrf-token"
    content="{{ csrf_token() }}"
>

{{-- =========================================================
    TÍTULO
========================================================= --}}

<title>
    @yield('title', 'PROCÁFES')
</title>


{{-- =========================================================
    DESCRIPCIÓN SEO
========================================================= --}}

<meta
    name="description"
    content="@yield(
        'description',
        'En PROCÁFES compartimos el aroma y la tradición del café peruano con bebidas preparadas para disfrutar cada momento.'
    )"
>


{{-- =========================================================
    URL CANÓNICA
========================================================= --}}

<link
    rel="canonical"
    href="{{ url()->current() }}"
>


{{-- =========================================================
    FAVICON Y LOGO
========================================================= --}}

<link
    rel="icon"
    type="image/jpeg"
    href="{{ asset('images/logo.jpg') }}"
>

<link
    rel="apple-touch-icon"
    href="{{ asset('images/logo.jpg') }}"
>


{{-- =========================================================
    OPEN GRAPH
========================================================= --}}

<meta
    property="og:type"
    content="website"
>

<meta
    property="og:site_name"
    content="PROCÁFES"
>

<meta
    property="og:title"
    content="@yield('title', 'PROCÁFES')"
>

<meta
    property="og:description"
    content="@yield(
        'description',
        'En PROCÁFES compartimos el aroma y la tradición del café peruano.'
    )"
>

<meta
    property="og:url"
    content="{{ url()->current() }}"
>

<meta
    property="og:image"
    content="{{ asset('images/logo.jpg') }}"
>


{{-- =========================================================
    TWITTER / X
========================================================= --}}

<meta
    name="twitter:card"
    content="summary"
>

<meta
    name="twitter:title"
    content="@yield('title', 'PROCÁFES')"
>

<meta
    name="twitter:description"
    content="@yield(
        'description',
        'En PROCÁFES compartimos el aroma y la tradición del café peruano.'
    )"
>

<meta
    name="twitter:image"
    content="{{ asset('images/logo.jpg') }}"
>


{{-- =========================================================
    DATOS ESTRUCTURADOS
========================================================= --}}

<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Organization",
    "name": "PROCÁFES",
    "url": "{{ url('/') }}",
    "logo": "{{ asset('images/logo.jpg') }}"
}
</script>


{{-- =========================================================
    VITE
========================================================= --}}

@vite([
    'resources/css/app.css',
    'resources/js/app.js'
])

@stack('styles')