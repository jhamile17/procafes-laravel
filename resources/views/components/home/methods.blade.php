@php
$methods = [
    [
        'image' => 'v60hario.png',
        'name' => 'V60 Hario',
        'description' => 'Método filtrado que resalta las notas suaves y florales.'
    ],
    [
        'image' => 'aeropress.png',
        'name' => 'AeroPress',
        'description' => 'Preparación rápida con cuerpo intenso y gran aroma.'
    ],
    [
        'image' => 'chemex.png',
        'name' => 'Chemex',
        'description' => 'Filtrado elegante que ofrece una taza limpia y balanceada.'
    ],
    [
        'image' => 'prensa.png',
        'name' => 'Prensa Francesa',
        'description' => 'Extracción completa para un café con mayor cuerpo.'
    ],
    [
        'image' => 'moka.png',
        'name' => 'Moka Italiana',
        'description' => 'Un clásico italiano con sabor intenso y tradicional.'
    ],
    [
        'image' => 'syphon.png',
        'name' => 'Syphon',
        'description' => 'Método de laboratorio que resalta aromas complejos.'
    ]
];
@endphp

<section class="methods-section">

    <div class="container">

        <div class="section-header">

            <h2 class="section-title">
                Métodos de preparación artesanal
            </h2>

            <p class="section-description">
                Descubre diferentes técnicas para disfrutar un café excepcional.
            </p>

        </div>

        <div class="methods-grid">

            @foreach($methods as $method)

                <div class="method-card">

                    <div class="method-circle">

                        <img
                            src="{{ asset('images/methods/'.$method['image']) }}"
                            alt="{{ $method['name'] }}">

                    </div>

                    <h3>{{ $method['name'] }}</h3>

                    <p>{{ $method['description'] }}</p>

                </div>

            @endforeach

        </div>

    </div>

</section>