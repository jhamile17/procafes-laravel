@props(['product'])

@php
    use Illuminate\Support\Facades\Storage;

    if (!empty($product->image_url)) {
        $image = $product->image_url;
    } elseif (!empty($product->image)) {
        $image = Storage::url($product->image);
    } else {
        $image = 'https://via.placeholder.com/600x600?text=PROCAFES';
    }
@endphp

<div class="card border-0 shadow-sm h-100 product-card">
    <div class="ratio ratio-1x1 overflow-hidden">
        <img
            src="{{ $image }}"
            alt="{{ $product->name }}"
            class="w-100 h-100 object-fit-cover">
    </div>

    <div class="card-body d-flex flex-column">
        @if($product->category)
            <small class="text-muted d-block">
                {{ $product->category->name }}
            </small>
        @endif

        @if($product->brand)
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
                    S/ {{ number_format($product->price, 2) }}
                </span>

                <span class="badge {{ $product->stock > 0 ? 'bg-success' : 'bg-secondary' }}">
                    {{ $product->stock > 0 ? 'Disponible' : 'Sin stock' }}
                </span>
            </div>

            <div class="d-grid gap-2">
                <button
                    type="button"
                    class="btn btn-dark btn-add-to-cart"
                    data-id="{{ $product->id }}"
                    data-name="{{ $product->name }}"
                    data-price="{{ $product->price }}"
                    data-image="{{ $image }}"
                    data-url="{{ route('products') }}"
                    {{ $product->stock <= 0 ? 'disabled' : '' }}>
                    <i class="bi bi-cart-plus me-1"></i>
                    Agregar al carrito
                </button>

                @auth
                    <button
                        type="button"
                        class="btn btn-outline-danger btn-wishlist"
                        data-id="{{ $product->id }}">
                        <i class="bi bi-heart me-1"></i>
                        Favoritos
                    </button>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-danger">
                        <i class="bi bi-heart me-1"></i>
                        Favoritos
                    </a>
                @endauth
            </div>
        </div>
    </div>
</div>