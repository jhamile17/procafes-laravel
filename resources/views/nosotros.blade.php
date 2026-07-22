@extends('layouts.app')

@section('title', 'Nosotros | PROCÁFES')

@section('content')

    {{-- Hero --}}
    <x-hero
        image="nosotros-hero.jpg"
        title="Nuestra historia,<br><span>nuestra pasión por el café</span>"
        subtitle="En PROCÁFES creemos que cada taza representa tradición, calidad y el compromiso de ofrecer una experiencia única a cada uno de nuestros clientes."
    />

    {{-- Nuestra Historia --}}
    <section class="section about-history">

        <div class="container">

            <div class="row align-items-center g-5">

                <div class="col-lg-6">

                    <div class="section-header text-start">

                        <span class="section-tag">
                            Nuestra Historia
                        </span>

                        <h2 class="section-title">
                            El café que une tradición, calidad y pasión.
                        </h2>

                        <div class="section-divider"></div>

                    </div>

                    <div class="about-history-content">

                        <p class="about-history-text">
                            En PROCÁFES creemos que una buena taza de café comienza mucho antes de ser servida. Inicia con la selección de granos de calidad, continúa con una preparación cuidadosa y termina creando momentos especiales para cada persona que nos visita.
                        </p>

                        <p class="about-history-text">
                            Nuestro compromiso es ofrecer una experiencia cálida, cercana y auténtica, donde cada bebida refleje la dedicación por el café peruano y el deseo de compartir su esencia con nuestros clientes.
                        </p>

                    </div>

                </div>

                <div class="col-lg-6">

                    <div class="about-history-image">

                        <img
                            src="{{ asset('images/nosotros/historia.jpg') }}"
                            alt="Historia de PROCÁFES"
                            class="img-fluid">

                    </div>

                </div>

            </div>

        </div>

    </section>

    {{-- Nuestra Esencia --}}
    <section class="section about-essence">

        <div class="container">

            <div class="section-header">

                <span class="section-tag">
                    Nuestra Esencia
                </span>

                <h2 class="section-title">
                    Los principios que guían cada taza que servimos.
                </h2>

                <div class="section-divider"></div>

                <p class="section-description">
                    Cada detalle en PROCÁFES refleja nuestro compromiso con la calidad, el servicio y la pasión por el auténtico café peruano.
                </p>

            </div>

            <div class="row g-4">

                <div class="col-lg-4">

                    <article class="essence-card">

                        <div class="essence-icon">

                            <i class="bi bi-cup-hot"></i>

                        </div>

                        <h3 class="essence-title">
                            Misión
                        </h3>

                        <p class="essence-text">
                            Brindar experiencias memorables a través de bebidas elaboradas con ingredientes de calidad y un servicio cercano que haga sentir a cada cliente como en casa.
                        </p>

                    </article>

                </div>

                <div class="col-lg-4">

                    <article class="essence-card">

                        <div class="essence-icon">

                            <i class="bi bi-bullseye"></i>

                        </div>

                        <h3 class="essence-title">
                            Visión
                        </h3>

                        <p class="essence-text">
                            Consolidarnos como una cafetería referente por la excelencia de nuestros productos, la innovación constante y el compromiso con nuestros clientes.
                        </p>

                    </article>

                </div>

                <div class="col-lg-4">

                    <article class="essence-card">

                        <div class="essence-icon">

                            <i class="bi bi-heart"></i>

                        </div>

                        <h3 class="essence-title">
                            Valores
                        </h3>

                        <p class="essence-text">
                            Calidad, compromiso, respeto, calidez e innovación son los valores que inspiran cada decisión y cada taza que servimos.
                        </p>

                    </article>

                </div>

            </div>

        </div>

    </section>

    {{-- ¿Por qué elegir PROCÁFES? --}}
    <section class="section about-benefits">

        <div class="container">

            <div class="row align-items-start g-5">

                <div class="col-lg-6">

                    <div class="about-benefits-image">

                        <img
                            src="{{ asset('images/nosotros/beneficios.jpg') }}"
                            alt="¿Por qué elegir PROCÁFES?"
                            class="img-fluid">

                    </div>

                </div>

                <div class="col-lg-6">

                    <div class="section-header text-start">

                        <span class="section-tag">
                            ¿Por qué elegirnos?
                        </span>

                        <h2 class="section-title">
                            Más que café, creamos experiencias memorables.
                        </h2>

                        <div class="section-divider"></div>

                    </div>

                    <ul class="about-benefits-list">

                        <li>
                            <i class="bi bi-check-circle-fill"></i>
                            Café peruano cuidadosamente seleccionado.
                        </li>

                        <li>
                            <i class="bi bi-check-circle-fill"></i>
                            Ingredientes frescos y de alta calidad.
                        </li>

                        <li>
                            <i class="bi bi-check-circle-fill"></i>
                            Ambiente cálido y acogedor para compartir.
                        </li>

                        <li>
                            <i class="bi bi-check-circle-fill"></i>
                            Atención cercana y personalizada.
                        </li>

                        <li>
                            <i class="bi bi-check-circle-fill"></i>
                            Pasión por cada bebida que servimos.
                        </li>

                    </ul>

                </div>

            </div>

        </div>

    </section>

@endsection