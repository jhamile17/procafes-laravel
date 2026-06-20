@props(['product'])

@php
    use Illuminate\Support\Facades\Storage;

    // Compatibilidad: preferir accessor `image_url`, luego `image` almacenada
    if (!empty($product->image_url)) {
        $image = $product->image_url;
    } elseif (!empty($product->image)) {
        $image = Storage::url($product->image);
    } else {
        $image = 'https://via.placeholder.com/600x600?text=PROCAFES';
    }
@endphp

<div class="card border-0 shadow-sm h-100 product-card">

    {{-- Imagen --}}
    <div class="ratio ratio-1x1 overflow-hidden">

        <img
            src="{{ $image }}"
            alt="{{ $product->name }}"
            class="w-100 h-100 object-fit-cover">

    </div>

    {{-- Contenido --}}
    <div class="card-body d-flex flex-column">

        @if(isset($product->category))
            <small class="text-muted d-block">
                {{ $product->category->name }}
            </small>
        @endif

        @if(isset($product->brand))
            <small class="text-muted">
                {{ $product->brand->name }}
            </small>
        @endif

        <h6 class="fw-semibold mt-1 mb-2">
            {{ $product->name }}
        </h6>

        <div class="mt-auto">

            <div class="d-flex justify-content-between align-items-center mb-3">

                <span class="fs-5 fw-bold">
                    S/ {{ number_format($product->price,2) }}
                </span>

                @if($product->stock > 0)

                    <span class="badge bg-success">
                        Disponible
                    </span>

                @else

                    <span class="badge bg-secondary">
                        Sin stock
                    </span>

                @endif

            </div>

            <div class="d-grid gap-2">

                <button
                    class="btn btn-dark btn-add-to-cart"
                    data-id="{{ $product->id }}"
                    {{ $product->stock <= 0 ? 'disabled' : '' }}>
                    🛒 Agregar al carrito
                </button>

                @auth

                    <button
                        class="btn btn-outline-danger btn-wishlist"
                        data-id="{{ $product->id }}">
                        ❤️ Favoritos
                    </button>

                @else

                    <a
                        href="{{ route('login') }}"
                        class="btn btn-outline-danger">
                        ❤️ Favoritos
                    </a>

                @endauth

            </div>

        </div>

    </div>

</div>

<style>
.product-card{
    transition:.25s ease;
}

.product-card:hover{
    transform:translateY(-6px);
}
</style>