@extends('layouts.admin')

@section('title', 'Registrar Producto')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="fw-bold mb-1">
                Registrar Producto
            </h2>

            <p class="text-muted mb-0">
                Agrega un nuevo producto al catálogo de PROCAFES.
            </p>

        </div>

        <a
            href="{{ route('admin.products.index') }}"
            class="btn btn-outline-secondary">

            <i class="bi bi-arrow-left me-2"></i>

            Volver

        </a>

    </div>

    @if ($errors->any())

        <div class="alert alert-danger">

            <strong>

                Se encontraron errores.

            </strong>

            <ul class="mb-0 mt-2">

                @foreach ($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif

    <form
        action="{{ route('admin.products.store') }}"
        method="POST"
        enctype="multipart/form-data">

        @csrf

        <div class="card border-0 shadow rounded-4">

            <div class="card-header bg-white">

                <h5 class="mb-0">

                    Información General

                </h5>

            </div>

            <div class="card-body">

                <div class="row g-4">

                    {{-- Categoría --}}
                    <div class="col-md-4">

                        <label class="form-label">

                            Categoría <span class="text-danger">*</span>

                        </label>

                        <select
                            name="categories_id"
                            class="form-select @error('categories_id') is-invalid @enderror">

                            <option value="">

                                Seleccione una categoría

                            </option>

                            @foreach($categories as $category)

                                <option
                                    value="{{ $category->id }}"
                                    @selected(old('categories_id') == $category->id)>

                                    {{ $category->name }}

                                </option>

                            @endforeach

                        </select>

                        @error('categories_id')

                            <div class="invalid-feedback">

                                {{ $message }}

                            </div>

                        @enderror

                    </div>

                    {{-- Marca --}}
                    <div class="col-md-4">

                        <label class="form-label">

                            Marca

                        </label>

                        <select
                            name="brand_id"
                            class="form-select @error('brand_id') is-invalid @enderror">

                            <option value="">

                                Seleccione una marca

                            </option>

                            @foreach($brands as $brand)

                                <option
                                    value="{{ $brand->id }}"
                                    @selected(old('brand_id') == $brand->id)>

                                    {{ $brand->name }}

                                </option>

                            @endforeach

                        </select>

                        @error('brand_id')

                            <div class="invalid-feedback">

                                {{ $message }}

                            </div>

                        @enderror

                    </div>

                    {{-- Tipo de consumo --}}
                    <div class="col-md-4">

                        <label class="form-label">

                            Tipo de consumo

                        </label>

                        <select
                            name="tipo_consumo_id"
                            class="form-select @error('tipo_consumo_id') is-invalid @enderror">

                            <option value="">

                                Seleccione un tipo

                            </option>

                            @foreach($tiposConsumo as $tipo)

                                <option
                                    value="{{ $tipo->id }}"
                                    @selected(old('tipo_consumo_id') == $tipo->id)>

                                    {{ $tipo->nombre }}

                                </option>

                            @endforeach

                        </select>

                        @error('tipo_consumo_id')

                            <div class="invalid-feedback">

                                {{ $message }}

                            </div>

                        @enderror

                    </div>

                                        {{-- Nombre del producto --}}
                    <div class="col-md-8">

                        <label class="form-label">

                            Nombre del producto
                            <span class="text-danger">*</span>

                        </label>

                        <input
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            class="form-control @error('name') is-invalid @enderror"
                            placeholder="Ej. Café Americano">

                        @error('name')

                            <div class="invalid-feedback">

                                {{ $message }}

                            </div>

                        @enderror

                    </div>

                    {{-- SKU --}}
                    <div class="col-md-4">

                        <label class="form-label">

                            SKU

                        </label>

                        <input
                            type="text"
                            name="sku"
                            value="{{ old('sku') }}"
                            class="form-control @error('sku') is-invalid @enderror"
                            placeholder="Se genera automáticamente">

                        <small class="text-muted">

                            Puede dejarse vacío.

                        </small>

                        @error('sku')

                            <div class="invalid-feedback">

                                {{ $message }}

                            </div>

                        @enderror

                    </div>

                    {{-- Código de barras --}}
                    <div class="col-md-6">

                        <label class="form-label">

                            Código de barras

                        </label>

                        <input
                            type="text"
                            name="barcode"
                            value="{{ old('barcode') }}"
                            class="form-control @error('barcode') is-invalid @enderror"
                            placeholder="Opcional">

                        @error('barcode')

                            <div class="invalid-feedback">

                                {{ $message }}

                            </div>

                        @enderror

                    </div>

                    {{-- Slug --}}
                    <div class="col-md-6">

                        <label class="form-label">

                            Slug

                        </label>

                        <input
                            type="text"
                            name="slug"
                            value="{{ old('slug') }}"
                            class="form-control @error('slug') is-invalid @enderror"
                            placeholder="Se genera automáticamente">

                        <small class="text-muted">

                            Puede dejarse vacío.

                        </small>

                        @error('slug')

                            <div class="invalid-feedback">

                                {{ $message }}

                            </div>

                        @enderror

                    </div>

                    {{-- Descripción --}}
                    <div class="col-12">

                        <label class="form-label">

                            Descripción

                        </label>

                        <textarea
                            rows="5"
                            name="description"
                            class="form-control @error('description') is-invalid @enderror"
                            placeholder="Describe el producto...">{{ old('description') }}</textarea>

                        @error('description')

                            <div class="invalid-feedback">

                                {{ $message }}

                            </div>

                        @enderror

                    </div>

                                        {{-- Precio de costo --}}
                    <div class="col-md-3">

                        <label class="form-label">

                            Precio de costo
                            <span class="text-danger">*</span>

                        </label>

                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            name="cost_price"
                            value="{{ old('cost_price') }}"
                            class="form-control @error('cost_price') is-invalid @enderror"
                            placeholder="0.00">

                        @error('cost_price')

                            <div class="invalid-feedback">

                                {{ $message }}

                            </div>

                        @enderror

                    </div>

                    {{-- Precio de venta --}}
                    <div class="col-md-3">

                        <label class="form-label">

                            Precio de venta
                            <span class="text-danger">*</span>

                        </label>

                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            name="sale_price"
                            value="{{ old('sale_price') }}"
                            class="form-control @error('sale_price') is-invalid @enderror"
                            placeholder="0.00">

                        @error('sale_price')

                            <div class="invalid-feedback">

                                {{ $message }}

                            </div>

                        @enderror

                    </div>

                    {{-- Stock --}}
                    <div class="col-md-2">

                        <label class="form-label">

                            Stock
                            <span class="text-danger">*</span>

                        </label>

                        <input
                            type="number"
                            min="0"
                            name="stock"
                            value="{{ old('stock',0) }}"
                            class="form-control @error('stock') is-invalid @enderror">

                        @error('stock')

                            <div class="invalid-feedback">

                                {{ $message }}

                            </div>

                        @enderror

                    </div>

                    {{-- Stock mínimo --}}
                    <div class="col-md-2">

                        <label class="form-label">

                            Stock mínimo

                        </label>

                        <input
                            type="number"
                            min="0"
                            name="stock_minimo"
                            value="{{ old('stock_minimo',5) }}"
                            class="form-control @error('stock_minimo') is-invalid @enderror">

                        @error('stock_minimo')

                            <div class="invalid-feedback">

                                {{ $message }}

                            </div>

                        @enderror

                    </div>

                    {{-- Estado --}}
                    <div class="col-md-2">

                        <label class="form-label">

                            Estado

                        </label>

                        <select
                            name="status"
                            class="form-select @error('status') is-invalid @enderror">

                            <option value="1"
                                @selected(old('status',1)==1)>

                                Activo

                            </option>

                            <option value="0"
                                @selected(old('status')==='0')>

                                Inactivo

                            </option>

                        </select>

                        @error('status')

                            <div class="invalid-feedback">

                                {{ $message }}

                            </div>

                        @enderror

                    </div>

                                        {{-- Imagen --}}
                    <div class="col-md-6">

                        <label class="form-label">

                            Imagen del producto

                        </label>

                        <input
                            type="file"
                            name="image"
                            id="image"
                            accept=".jpg,.jpeg,.png,.webp"
                            class="form-control @error('image') is-invalid @enderror">

                        @error('image')

                            <div class="invalid-feedback">

                                {{ $message }}

                            </div>

                        @enderror

                        <small class="text-muted">

                            Formatos permitidos:
                            JPG, PNG y WEBP.
                            Máximo 2 MB.

                        </small>

                    </div>

                    {{-- Vista previa --}}
                    <div class="col-md-6 text-center">

                        <label class="form-label d-block">

                            Vista previa

                        </label>

                        <img

                            id="preview-image"

                            src="{{ asset('images/no-image.png') }}"

                            class="img-thumbnail shadow-sm"

                            style="max-width:220px;max-height:220px;object-fit:cover;">

                    </div>

                </div>

            </div>

            <div class="card-footer bg-white">

                <div class="d-flex justify-content-end gap-2">

                    <a

                        href="{{ route('admin.products.index') }}"

                        class="btn btn-outline-secondary">

                        Cancelar

                    </a>

                    <button

                        type="submit"

                        class="btn btn-warning px-4">

                        <i class="bi bi-check-circle me-2"></i>

                        Guardar Producto

                    </button>

                </div>

            </div>

        </div>

    </form>

</div>

@endsection


@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', () => {

    const input = document.getElementById('image');

    const preview = document.getElementById('preview-image');

    input.addEventListener('change', function(e){

        if(!e.target.files.length){

            return;

        }

        const reader = new FileReader();

        reader.onload = function(event){

            preview.src = event.target.result;

        }

        reader.readAsDataURL(e.target.files[0]);

    });

});

</script>

@endpush