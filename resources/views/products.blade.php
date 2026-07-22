@extends('layouts.app')

@section('title', 'Productos - PROCAFES')

@section('content')
@php
    $selected = request('categoria');
    $search = request('search');
@endphp

<div class="container py-4">
    <x-products.categories
        :categories="$categories"
        :selected="$selected" />

    <section class="mt-4">

        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2 mb-3">

            <div>

                <h1 class="h4 mb-1">
                    Todos los productos
                </h1>

                @if($search)
                    <p class="text-muted mb-0">
                        Resultados para:
                        <strong>{{ $search }}</strong>
                    </p>
                @endif

            </div>

            <span class="text-muted">
                {{ $products->total() }} resultado(s)
            </span>

        </div>

        @if($products->isEmpty())

            <div class="alert alert-info">
                No hay productos disponibles con esos filtros.
            </div>

        @else

            <div class="row g-3">

                @foreach($products as $product)

                    <div class="col-6 col-md-4 col-lg-3">

                        <x-ecommerce.product-card
                            :product="$product" />

                    </div>

                @endforeach

            </div>

            <div class="mt-4 d-flex justify-content-center">
                {{ $products->links('vendor.pagination.paginacion') }}            </div>

        @endif

    </section>

</div>

@endsection