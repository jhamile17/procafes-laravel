@extends('layouts.app')

@section('title', 'Productos - PROCAFES')

@section('content')
@php
    $selected = request('categoria');
    $search = request('search');
@endphp

<div class="container py-4">
    <div class="row g-4">

        <aside class="col-lg-3">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-procafes">
                    <strong>Categorías</strong>
                </div>

                <div class="list-group list-group-flush">
                    <a href="{{ route('products') }}"
                       class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ !$selected ? 'active' : '' }}">
                        <span>Todas</span>
                        <span class="badge bg-secondary rounded-pill">{{ $products->total() }}</span>
                    </a>

                    @foreach($categories as $cat)
                        <a href="{{ route('products', ['categoria' => $cat->id]) }}"
                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ (string) $selected === (string) $cat->id ? 'active' : '' }}">
                            <span>{{ $cat->name }}</span>
                            <span class="badge bg-secondary rounded-pill">{{ $counts[$cat->id] ?? 0 }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </aside>

        <section class="col-lg-9">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2 mb-3">
                <div>
                    <h1 class="h4 mb-1">Todos los productos</h1>

                    @if($search)
                        <p class="text-muted mb-0">
                            Resultados para: <strong>{{ $search }}</strong>
                        </p>
                    @endif
                </div>

                <span class="text-muted">{{ $products->total() }} resultado(s)</span>
            </div>

            @if($products->isEmpty())
                <div class="alert alert-info">
                    No hay productos disponibles con esos filtros.
                </div>
            @else
                <div class="row g-3">
                    @foreach($products as $product)
                        <div class="col-6 col-md-4">
                            <x-ecommerce.product-card :product="$product" />
                        </div>
                    @endforeach
                </div>

                <div class="mt-4 d-flex justify-content-center">
                    {{ $products->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </section>

    </div>
</div>
@endsection