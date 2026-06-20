@extends('layouts.app')

@section('title','Inicio | PROCAFES')

@section('content')

<section class="hero">
    ...
</section>

<section class="container py-5">

    <div class="text-center mb-5">

        <h2 class="fw-bold">
            Productos Destacados
        </h2>

        <p class="text-muted">
            Descubre nuestros mejores cafés
        </p>

    </div>

    <div class="row g-4">

        @foreach($products as $product)

            <div class="col-6 col-md-4 col-lg-3">

                <x-ecommerce.product-card
                    :product="$product" />

            </div>

        @endforeach

    </div>

</section>

@endsection