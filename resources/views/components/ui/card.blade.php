@props(['product'])

<div class="card border-0 shadow-sm h-100 product-card">

    {{-- IMAGE --}}
    <div class="overflow-hidden rounded-top">
        <img src="{{ asset('storage/'.$product->image) }}"
             class="w-100 product-img"
             alt="{{ $product->name }}">
    </div>

    {{-- BODY --}}
    <div class="card-body d-flex flex-column">

        <h6 class="fw-bold mb-1">{{ $product->name }}</h6>

        <small class="text-muted mb-2">
            {{ $product->category->name ?? 'Sin categoría' }}
        </small>

        <p class="fw-bold text-dark mb-3">
            S/ {{ number_format($product->price, 2) }}
        </p>

        <div class="mt-auto d-grid gap-2">

            <button class="btn btn-dark btn-sm rounded-pill">
                Agregar al carrito
            </button>

            <button class="btn btn-outline-dark btn-sm rounded-pill">
                Ver detalle
            </button>

        </div>
    </div>
</div>