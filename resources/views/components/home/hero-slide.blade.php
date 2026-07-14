@props([
    'image',
    'title',
    'subtitle',
    'button',
    'url',
    'active' => false,
])
<div class="carousel-item {{ $active ? 'active' : '' }}">
    <div
        class="hero-slide"
        style="background-image:url('{{ asset('images/hero/'.$image) }}')">
        <div class="hero-overlay"></div>
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">
                    {!! $title !!}
                </h1>
                <p class="hero-description">
                    {{ $subtitle }}
                </p>
                <div class="hero-actions">
                    <a
                        href="{{ $url }}"
                        class="btn btn-danger hero-button">
                        {{$button}}
                        <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>