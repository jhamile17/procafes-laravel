<meta charset="utf-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1"
>

<meta
    name="csrf-token"
    content="{{ csrf_token() }}"
>

<title>@yield('title', 'PROCÁFES')</title>

<meta
    name="description"
    content="@yield('description', 'En PROCÁFES compartimos el aroma y la tradición del café peruano con bebidas preparadas para disfrutar cada momento.')"
>

<link
    rel="canonical"
    href="{{ url()->current() }}"
>

{{-- FAVICON --}}
<link
    rel="icon"
    type="image/x-icon"
    href="{{ asset('favicon.ico') }}"
>

<link
    rel="apple-touch-icon"
    href="{{ asset('images/logo.jpg') }}"
>

{{-- OPEN GRAPH --}}
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
    content="@yield('description', 'En PROCÁFES compartimos el aroma y la tradición del café peruano.')"
>

<meta
    property="og:url"
    content="{{ url()->current() }}"
>

<meta
    property="og:image"
    content="{{ asset('images/logo.jpg') }}"
>

{{-- TWITTER --}}
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
    content="@yield('description', 'En PROCÁFES compartimos el aroma y la tradición del café peruano.')"
>

<meta
    name="twitter:image"
    content="{{ asset('images/logo.jpg') }}"
>

{{-- VITE --}}
@vite([
    'resources/css/app.css',
    'resources/js/app.js'
])

@stack('styles')