<section class="hero-section">

    <div class="hero-container">

        <div
            id="heroCarousel"
            class="carousel slide carousel-fade"
            data-bs-ride="carousel">

            <div class="carousel-inner">

                <div class="carousel-item active">

                    <x-home.hero-slide
                        image="hero-1.jpg"
                        title="El sabor del café peruano,<br><span>más cerca de ti</span>"
                        subtitle="En PROCÁFES compartimos el aroma y la tradición del café peruano con bebidas preparadas para disfrutar cada momento."
                        button="Ver productos"
                        :url="route('products')"
                    />

                </div>

                <div class="carousel-item">

                    <x-home.hero-slide
                        image="hero-2.jpg"
                        title="Cada taza<br><span>se prepara con dedicación</span>"
                        subtitle="Creemos que un buen café comienza con la pasión por cada detalle. Desde la selección del grano hasta el último toque."
                        button="Conoce nuestra historia"
                        :url="route('nosotros')"
                    />

                </div>

                <div class="carousel-item">

                    <x-home.hero-slide
                        image="hero-3.jpg"
                        title="Mucho más<br><span>que café</span>"
                        subtitle="Frappés, chocolates, tés y bebidas preparadas para acompañarte en cualquier momento del día."
                        button="Ver menú"
                        :url="route('products')"
                    />

                </div>

            </div>

            <button
                class="carousel-control-prev"
                type="button"
                data-bs-target="#heroCarousel"
                data-bs-slide="prev">

                <span
                    class="carousel-control-prev-icon"
                    aria-hidden="true">
                </span>

                <span class="visually-hidden">
                    Anterior
                </span>

            </button>

            <button
                class="carousel-control-next"
                type="button"
                data-bs-target="#heroCarousel"
                data-bs-slide="next">

                <span
                    class="carousel-control-next-icon"
                    aria-hidden="true">
                </span>

                <span class="visually-hidden">
                    Siguiente
                </span>

            </button>

            <div class="carousel-indicators">

                <button
                    type="button"
                    data-bs-target="#heroCarousel"
                    data-bs-slide-to="0"
                    class="active"
                    aria-current="true"
                    aria-label="Slide 1">
                </button>

                <button
                    type="button"
                    data-bs-target="#heroCarousel"
                    data-bs-slide-to="1"
                    aria-label="Slide 2">
                </button>

                <button
                    type="button"
                    data-bs-target="#heroCarousel"
                    data-bs-slide-to="2"
                    aria-label="Slide 3">
                </button>

            </div>

        </div>

    </div>

</section>