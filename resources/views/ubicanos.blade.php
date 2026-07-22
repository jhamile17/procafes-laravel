@extends('layouts.app')

@section('title', 'Ubícanos | PROCÁFES')

@section('content')

@php
    $mapsQuery = urlencode(
        $empresa->nombre_empresa . ' ' . $empresa->direccion
    );

    $gmapLink = "https://www.google.com/maps/search/?api=1&query={$mapsQuery}";
@endphp

{{-- Hero --}}
<x-hero
    image="ubicanos-hero.jpg"
    title="Visítanos,<br><span>te esperamos con el mejor café</span>"
    subtitle="Descubre un espacio acogedor donde el aroma, el sabor y la pasión por el café se unen para brindarte una experiencia única."
/>

{{-- Información de contacto --}}
<section class="location-info">

    <div class="container">

        <div class="section-header">

            <span class="section-tag">
                Ubicación
            </span>

            <h2 class="section-title">
                Encuéntranos fácilmente
            </h2>

            <div class="section-divider"></div>

            <p class="section-description">
                Te esperamos en un ambiente cálido y acogedor, donde cada taza de café está preparada con dedicación para brindarte una experiencia única.
            </p>

        </div>

        <div class="row g-4">

            {{-- Dirección --}}
            <div class="col-lg-3 col-md-6">

                <article class="location-card">

                    <div class="location-icon">
                        <i class="bi bi-geo-alt"></i>
                    </div>

                    <h3 class="location-title">
                        Dirección
                    </h3>

                    <p class="location-text">
                        {{ $empresa->direccion }}
                    </p>

                </article>

            </div>

            {{-- Teléfono --}}
            <div class="col-lg-3 col-md-6">

                <article class="location-card">

                    <div class="location-icon">
                        <i class="bi bi-telephone"></i>
                    </div>

                    <h3 class="location-title">
                        Teléfono
                    </h3>

                    <p class="location-text">
                        {{ $empresa->telefono }}
                    </p>

                </article>

            </div>

            {{-- Horario --}}
            <div class="col-lg-3 col-md-6">

                <article class="location-card">

                    <div class="location-icon">
                        <i class="bi bi-clock"></i>
                    </div>

                    <h3 class="location-title">
                        Horario
                    </h3>

                    <p class="location-text">
                        Lun - Dom<br>
                        8:00 AM - 8:00 PM
                    </p>

                </article>

            </div>

            {{-- Correo --}}
            <div class="col-lg-3 col-md-6">

                <article class="location-card">

                    <div class="location-icon">
                        <i class="bi bi-envelope"></i>
                    </div>

                    <h3 class="location-title">
                        Correo
                    </h3>

                    <p class="location-text">
                        {{ $empresa->correo }}
                    </p>

                </article>

            </div>

        </div>

    </div>

</section>

{{-- Mapa --}}
<section class="location-map">

    <div class="container">

        <div class="section-header">

            <span class="section-tag">
                Mapa
            </span>

            <h2 class="section-title">
                Nuestra ubicación
            </h2>

            <div class="section-divider"></div>

            <p class="section-description">
                Encuéntranos fácilmente y disfruta de un ambiente diseñado para compartir el mejor café y momentos inolvidables.
            </p>

        </div>

        <div class="location-map-card">

            <iframe
                src="https://www.google.com/maps?q={{ $mapsQuery }}&output=embed"
                loading="lazy"
                allowfullscreen
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>

        </div>

    </div>

</section>

{{-- CTA --}}
<section class="location-cta">

    <div class="container">

        <div class="location-cta-content">

            <span class="section-tag">
                Te esperamos
            </span>

            <h2 class="location-cta-title">
                Estamos listos para recibirte
            </h2>

            <p class="location-cta-description">
                Comparte un buen café con nosotros en un ambiente cálido y acogedor. En PROCÁFES cada visita está pensada para brindarte una experiencia especial.
            </p>

            <div class="location-cta-buttons">

                <a
                    href="{{ $gmapLink }}"
                    target="_blank"
                    class="btn-primary-custom">

                    <i class="bi bi-geo-alt"></i>

                    Abrir en Google Maps

                </a>

                <a
                    href="tel:{{ preg_replace('/\D/', '', $empresa->telefono) }}"
                    class="btn-secondary-custom">

                    <i class="bi bi-telephone"></i>

                    Llamar ahora

                </a>

            </div>

        </div>

    </div>

</section>

@endsection