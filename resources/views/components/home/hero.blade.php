<section class="hero-section">

    <div class="hero-container">

        <div
            id="heroCarousel"
            class="carousel slide carousel-fade"
            data-bs-ride="carousel">

            <div class="carousel-inner">

                <x-home.hero-slide
                    active="true"
                    image="hero-1.jpg"
                    title="Momentos únicos,<br><span>sabor inolvidable</span>"
                    subtitle="Descubre nuestros cafés premium, bebidas artesanales y productos seleccionados para disfrutar cada momento."
                />

                <x-home.hero-slide
                    image="hero-2.jpg"
                    title="Cada taza cuenta una historia"
                    subtitle="El mejor café comienza con ingredientes de calidad y una preparación perfecta."
                />

                <x-home.hero-slide
                    image="hero-3.jpg"
                    title="Comparte grandes momentos"
                    subtitle="Frappés, chocolates, cafés calientes y mucho más para disfrutar en familia."
                />

            </div>

            <button
                class="carousel-control-prev"
                data-bs-target="#heroCarousel"
                data-bs-slide="prev">

                <span class="carousel-control-prev-icon"></span>

            </button>

            <button
                class="carousel-control-next"
                data-bs-target="#heroCarousel"
                data-bs-slide="next">

                <span class="carousel-control-next-icon"></span>

            </button>

            <div class="carousel-indicators">

                <button data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>

                <button data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>

                <button data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>

            </div>

        </div>

    </div>

</section>