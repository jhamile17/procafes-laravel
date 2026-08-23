@props(['product'])

<div class="procafe-product-card">

    {{-- ==========================================================
        IMAGEN
    =========================================================== --}}

    <div class="procafe-product-image">

        <img
            src="{{ $product->image_url }}"
            alt="{{ $product->name }}"
        >

        <x-ecommerce.product.badge
            :product="$product"
        />

        <x-ecommerce.product.wishlist-button
            :product="$product"
        />

    </div>


    {{-- ==========================================================
        INFORMACIÓN
    =========================================================== --}}

    <div class="card-body d-flex flex-column">

        @if($product->category)

            <small class="procafe-product-category">
                {{ $product->category->name }}
            </small>

        @endif


        <h5 class="procafe-product-title">
            {{ $product->name }}
        </h5>


        @if($product->brand)

            <small class="procafe-product-brand">
                {{ $product->brand->name }}
            </small>

        @endif


        <div class="mt-auto">

            {{-- ==================================================
                PRECIO
            =================================================== --}}

            <x-ecommerce.product.price
                :product="$product"
            />


            {{-- ==================================================
                VER DETALLE
            =================================================== --}}

            <button
                type="button"
                class="product-detail-btn"
                data-bs-toggle="modal"
                data-bs-target="#productDetailModal{{ $product->id }}"
            >

                <i class="bi bi-eye"></i>

                <span>
                    Ver detalle
                </span>

            </button>


            {{-- ==================================================
                AGREGAR AL CARRITO
            =================================================== --}}

            <x-ecommerce.product.add-cart-button
                :product="$product"
                :image="$product->image_url"
            />

        </div>

    </div>

</div>


{{-- ==========================================================
    MODAL DETALLE DEL PRODUCTO
=========================================================== --}}

<div
    class="modal fade product-detail-modal"
    id="productDetailModal{{ $product->id }}"
    tabindex="-1"
    aria-labelledby="productDetailModalLabel{{ $product->id }}"
    aria-hidden="true"
>

    <div class="modal-dialog modal-dialog-centered modal-lg">

        <div class="modal-content">


            {{-- ==================================================
                HEADER
            =================================================== --}}

            <div class="modal-header">

                <h5
                    class="modal-title"
                    id="productDetailModalLabel{{ $product->id }}"
                >
                    Detalle del producto
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Cerrar"
                ></button>

            </div>


            {{-- ==================================================
                BODY
            =================================================== --}}

            <div class="modal-body">

                <div class="product-detail-content">


                    {{-- ==================================================
                        IMAGEN
                    =================================================== --}}

                    <div class="product-detail-image">

                        <img
                            src="{{ $product->image_url }}"
                            alt="{{ $product->name }}"
                        >

                    </div>


                    {{-- ==================================================
                        INFORMACIÓN
                    =================================================== --}}

                    <div class="product-detail-info">

                        @if($product->category)

                            <span class="product-detail-category">
                                {{ $product->category->name }}
                            </span>

                        @endif


                        <h2>
                            {{ $product->name }}
                        </h2>


                        @if($product->brand)

                            <div class="product-detail-brand">

                                <i class="bi bi-award"></i>

                                {{ $product->brand->name }}

                            </div>

                        @endif


                        {{-- ==================================================
                            PRECIO
                        =================================================== --}}

                        <div class="product-detail-price">

                            {{ $product->precio_formateado }}

                        </div>


                        {{-- ==================================================
                            STOCK
                        =================================================== --}}

                        <div class="product-detail-stock">

                            <i class="bi bi-box-seam"></i>

                            {{ $product->stock_status }}

                        </div>


                        {{-- ==================================================
                            DESCRIPCIÓN
                        =================================================== --}}

                        @if($product->description)

                            <div class="product-detail-description">

                                <h6>
                                    Descripción
                                </h6>

                                <p>
                                    {{ $product->description }}
                                </p>

                            </div>

                        @endif


                        {{-- ==================================================
                            TEMPERATURA
                        =================================================== --}}

                        @if($product->temperature)

                            <div class="product-detail-meta">

                                <div>

                                    <i class="bi bi-thermometer-half"></i>

                                    <span>
                                        {{ $product->temperature }}
                                    </span>

                                </div>

                            </div>

                        @endif


                        {{-- ==================================================
                            AGREGAR AL CARRITO
                        =================================================== --}}

                        <div class="product-detail-action">

                            <x-ecommerce.product.add-cart-button
                                :product="$product"
                                :image="$product->image_url"
                            />

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>