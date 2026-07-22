@props([
    'methods'
])
<section class="methods-section">

    <div class="container">

        <div class="methods-header">

            <div class="methods-info">

                <span class="section-subtitle">
                    Cultura Cafetera
                </span>

                <h2 class="section-title">
                    Métodos de Preparación
                </h2>

                <p class="section-description">
                    Descubre diferentes formas de preparar un excelente café.
                </p>

            </div>

            <a
                href="{{ route('products', ['category' => 'accesorios']) }}"
                class="btn-link-arrow"
                aria-label="Ver accesorios para métodos de preparación">

                <span>Ver accesorios</span>

                <i
                    class="bi bi-arrow-right"
                    aria-hidden="true">
                </i>

            </a>

        </div>

        <div class="methods-wrapper">

            <button
                class="methods-arrow methods-prev"
                type="button"
                aria-label="Método anterior">

                <i
                    class="bi bi-chevron-left"
                    aria-hidden="true">
                </i>

            </button>

            <div
                id="methodsSlider"
                class="methods-slider"
                aria-label="Carrusel de métodos de preparación">

                @foreach($methods as $method)

                    <article class="method-item fade-up">

                        <div class="method-image">

                            <img
                                src="{{ asset('images/methods/'.$method['image']) }}"
                                alt="{{ $method['name'] }}"
                                loading="lazy"
                                decoding="async">

                        </div>

                        <h3>{{ $method['name'] }}</h3>

                        <p>{{ $method['description'] }}</p>

                    </article>

                @endforeach

            </div>

            <button
                class="methods-arrow methods-next"
                type="button"
                aria-label="Siguiente método">

                <i
                    class="bi bi-chevron-right"
                    aria-hidden="true">
                </i>

            </button>

        </div>

    </div>

</section>