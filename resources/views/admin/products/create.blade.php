@extends('layouts.admin')

@section('title', 'Registrar Producto')

@section('content')

<div class="admin-form-page">

    {{-- =====================================================
         ENCABEZADO
    ====================================================== --}}

    <div class="admin-form-header">

        <div class="admin-form-heading">

            <div class="admin-form-heading-icon">
                <i class="bi bi-cup-hot-fill"></i>
            </div>

            <div>

                <h1 class="admin-form-title">
                    Registrar producto
                </h1>

                <p class="admin-form-subtitle">
                    Agrega un nuevo producto al catálogo de PROCÁFES.
                </p>

            </div>

        </div>


        <a
            href="{{ route('admin.products.index') }}"
            class="admin-form-btn admin-form-btn-save"
        >

            <i class="bi bi-arrow-left"></i>

            Volver a productos

        </a>

    </div>

    {{-- =====================================================
         FORMULARIO
    ====================================================== --}}

    <form
        action="{{ route('admin.products.store') }}"
        method="POST"
        enctype="multipart/form-data"
        class="admin-form"
    >

        @csrf


        {{-- =================================================
             INFORMACIÓN DEL PRODUCTO
        ================================================== --}}

        <section class="admin-form-card">

            <div class="admin-form-card-header">

                <div class="admin-form-section-icon">

                    <i class="bi bi-cup-hot-fill"></i>

                </div>

                <div>

                    <h2 class="admin-form-card-title">
                        Información del producto
                    </h2>

                    <p class="admin-form-card-subtitle">
                        Datos principales del producto.
                    </p>

                </div>

            </div>


            <div class="admin-form-card-body">

                <div class="admin-form-grid">

                    {{-- CATEGORÍA --}}

                    <div class="admin-form-field">

                        <label
                            for="categories_id"
                            class="admin-form-label"
                        >

                            Categoría
                            <span>*</span>

                        </label>

                        <select
                            id="categories_id"
                            name="categories_id"
                            class="admin-form-input @error('categories_id') is-invalid @enderror"
                        >

                            <option value="">
                                Seleccione una categoría
                            </option>

                            @foreach($categories as $category)

                                <option
                                    value="{{ $category->id }}"
                                    @selected(
                                        old('categories_id') == $category->id
                                    )
                                >

                                    {{ $category->name }}

                                </option>

                            @endforeach

                        </select>

                        @error('categories_id')

                            <div class="admin-form-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- MARCA --}}

                    <div class="admin-form-field">

                        <label
                            for="brand_id"
                            class="admin-form-label"
                        >
                            Marca
                        </label>

                        <select
                            id="brand_id"
                            name="brand_id"
                            class="admin-form-input @error('brand_id') is-invalid @enderror"
                        >

                            <option value="">
                                Seleccione una marca
                            </option>

                            @foreach($brands as $brand)

                                <option
                                    value="{{ $brand->id }}"
                                    @selected(
                                        old('brand_id') == $brand->id
                                    )
                                >

                                    {{ $brand->name }}

                                </option>

                            @endforeach

                        </select>

                        @error('brand_id')

                            <div class="admin-form-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- TIPO DE CONSUMO --}}

                    <div class="admin-form-field">

                        <label
                            for="tipo_consumo_id"
                            class="admin-form-label"
                        >
                            Tipo de consumo
                        </label>

                        <select
                            id="tipo_consumo_id"
                            name="tipo_consumo_id"
                            class="admin-form-input @error('tipo_consumo_id') is-invalid @enderror"
                        >

                            <option value="">
                                Seleccione un tipo
                            </option>

                            @foreach($tiposConsumo as $tipo)

                                <option
                                    value="{{ $tipo->id }}"
                                    @selected(
                                        old('tipo_consumo_id') == $tipo->id
                                    )
                                >

                                    {{ $tipo->nombre }}

                                </option>

                            @endforeach

                        </select>

                        @error('tipo_consumo_id')

                            <div class="admin-form-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- NOMBRE --}}

                    <div class="admin-form-field admin-form-field-wide">

                        <label
                            for="name"
                            class="admin-form-label"
                        >

                            Nombre del producto
                            <span>*</span>

                        </label>

                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            class="admin-form-input @error('name') is-invalid @enderror"
                            placeholder="Ej. Café Americano"
                        >

                        @error('name')

                            <div class="admin-form-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- SKU --}}

                    <div class="admin-form-field">

                        <label
                            for="sku"
                            class="admin-form-label"
                        >
                            SKU
                        </label>

                        <input
                            type="text"
                            id="sku"
                            name="sku"
                            value="{{ old('sku') }}"
                            class="admin-form-input @error('sku') is-invalid @enderror"
                            placeholder="Ej. CAF-001"
                        >

                        <small class="admin-form-help">
                            Puede dejarse vacío.
                        </small>

                        @error('sku')

                            <div class="admin-form-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- DESCRIPCIÓN --}}

                    <div class="admin-form-field admin-form-field-full">

                        <label
                            for="description"
                            class="admin-form-label"
                        >
                            Descripción
                        </label>

                        <textarea
                            id="description"
                            name="description"
                            rows="4"
                            class="admin-form-input @error('description') is-invalid @enderror"
                            placeholder="Describe el producto..."
                        >{{ old('description') }}</textarea>

                        @error('description')

                            <div class="admin-form-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>

            </div>

        </section>


        {{-- =================================================
             PRECIO E INVENTARIO
        ================================================== --}}

        <section class="admin-form-card">

            <div class="admin-form-card-header">

                <div class="admin-form-section-icon admin-form-section-icon-gold">

                    <i class="bi bi-box-seam"></i>

                </div>

                <div>

                    <h2 class="admin-form-card-title">
                        Precio e inventario
                    </h2>

                    <p class="admin-form-card-subtitle">
                        Define el precio y la disponibilidad inicial.
                    </p>

                </div>

            </div>


            <div class="admin-form-card-body">

                <div class="admin-form-grid admin-form-grid-four">

                    {{-- PRECIO DE VENTA --}}

                    <div class="admin-form-field">

                        <label
                            for="sale_price"
                            class="admin-form-label"
                        >

                            Precio de venta
                            <span>*</span>

                        </label>

                        <div class="admin-form-price">

                            <span class="admin-form-price-prefix">
                                S/
                            </span>

                            <input
                                type="number"
                                id="sale_price"
                                name="sale_price"
                                step="0.01"
                                min="0"
                                value="{{ old('sale_price') }}"
                                class="admin-form-input admin-form-price-input @error('sale_price') is-invalid @enderror"
                                placeholder="0.00"
                            >

                        </div>

                        @error('sale_price')

                            <div class="admin-form-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- STOCK --}}

                    <div class="admin-form-field">

                        <label
                            for="stock"
                            class="admin-form-label"
                        >

                            Stock
                            <span>*</span>

                        </label>

                        <input
                            type="number"
                            id="stock"
                            name="stock"
                            min="0"
                            value="{{ old('stock', 0) }}"
                            class="admin-form-input @error('stock') is-invalid @enderror"
                        >

                        @error('stock')

                            <div class="admin-form-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- STOCK MÍNIMO --}}

                    <div class="admin-form-field">

                        <label
                            for="stock_minimo"
                            class="admin-form-label"
                        >
                            Stock mínimo
                        </label>

                        <input
                            type="number"
                            id="stock_minimo"
                            name="stock_minimo"
                            min="0"
                            value="{{ old('stock_minimo', 5) }}"
                            class="admin-form-input @error('stock_minimo') is-invalid @enderror"
                        >

                        @error('stock_minimo')

                            <div class="admin-form-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- ESTADO --}}

                    <div class="admin-form-field">

                        <label
                            for="status"
                            class="admin-form-label"
                        >
                            Estado
                        </label>

                        <select
                            id="status"
                            name="status"
                            class="admin-form-input @error('status') is-invalid @enderror"
                        >

                            <option
                                value="1"
                                @selected(old('status', 1) == 1)
                            >
                                Activo
                            </option>

                            <option
                                value="0"
                                @selected(old('status') === '0')
                            >
                                Inactivo
                            </option>

                        </select>

                        @error('status')

                            <div class="admin-form-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>

            </div>

        </section>


        {{-- =================================================
             IMAGEN
        ================================================== --}}

        <section class="admin-form-card">

            <div class="admin-form-card-header">

                <div class="admin-form-section-icon">

                    <i class="bi bi-image"></i>

                </div>

                <div>

                    <h2 class="admin-form-card-title">
                        Imagen del producto
                    </h2>

                    <p class="admin-form-card-subtitle">
                        Agrega una imagen representativa del producto.
                    </p>

                </div>

            </div>


            <div class="admin-form-card-body">

                <div class="admin-form-image-layout">

                    {{-- ARCHIVO --}}

                    <div class="admin-form-upload">

                        <label
                            for="image"
                            class="admin-form-label"
                        >
                            Imagen del producto
                        </label>

                        <input
                            type="file"
                            id="image"
                            name="image"
                            accept=".jpg,.jpeg,.png,.webp"
                            class="admin-form-input @error('image') is-invalid @enderror"
                        >

                        @error('image')

                            <div class="admin-form-error">
                                {{ $message }}
                            </div>

                        @enderror

                        <small class="admin-form-help">
                            JPG, PNG o WEBP. Máximo 2 MB.
                        </small>

                    </div>


                    {{-- VISTA PREVIA --}}

                    <div class="admin-form-preview-wrapper">

                        <span class="admin-form-label">
                            Vista previa
                        </span>

                        <div class="admin-form-preview">

                            <img
                                id="preview-image"
                                src="{{ asset('images/no-image.png') }}"
                                alt="Vista previa del producto"
                            >

                        </div>

                    </div>

                </div>

            </div>

        </section>


        {{-- =================================================
             ACCIONES
        ================================================== --}}

        <div class="admin-form-actions">

            <a
                href="{{ route('admin.products.index') }}"
                class="admin-form-btn admin-form-btn-cancel"
            >

                <i class="bi bi-x-lg"></i>

                Cancelar

            </a>

            <button
                type="submit"
                class="admin-form-btn admin-form-btn-save"
            >

                <i class="bi bi-check-circle-fill"></i>

                Guardar producto

            </button>

        </div>

    </form>

</div>

@endsection


@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    const input = document.getElementById('image');
    const preview = document.getElementById('preview-image');

    if (!input || !preview) {
        return;
    }

    input.addEventListener('change', function (event) {

        const file = event.target.files[0];

        if (!file) {
            return;
        }

        const reader = new FileReader();

        reader.onload = function (event) {

            preview.src = event.target.result;

        };

        reader.readAsDataURL(file);

    });

});

</script>

@endpush