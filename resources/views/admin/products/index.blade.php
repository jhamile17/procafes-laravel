@extends('layouts.admin')

@section('title', 'Productos | PROCÁFES')

@section('content')

<div class="products-page">

    {{-- =====================================================
         ENCABEZADO
    ====================================================== --}}

    <div class="products-header d-flex justify-content-between align-items-center flex-wrap gap-3 mb-3">

        <div>

            <h2 class="products-title fw-bold mb-1">
                Productos
            </h2>

            <p class="products-subtitle mb-0">
                Administra el catálogo de productos de PROCÁFES.
            </p>

        </div>

        <a
            href="{{ route('admin.products.create') }}"
            class="admin-form-btn admin-form-btn-save">

            <i class="bi bi-plus-circle me-2"></i>

            Nuevo producto

        </a>

    </div>


    {{-- =====================================================
         MENSAJES
    ====================================================== --}}

    @if(session('success'))

        <div
            class="alert alert-success alert-dismissible fade show"
            role="alert">

            <i class="bi bi-check-circle-fill me-2"></i>

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    @if(session('error'))

        <div
            class="alert alert-danger alert-dismissible fade show"
            role="alert">

            <i class="bi bi-exclamation-triangle-fill me-2"></i>

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- =====================================================
         FILTROS
    ====================================================== --}}

    <div class="card products-filter-card mb-3">

        <div class="card-body">

            <form
                method="GET"
                action="{{ route('admin.products.index') }}">

                <div class="row g-3 align-items-end">

                    {{-- Buscar --}}

                    <div class="col-lg-4">

                        <label
                            for="buscar"
                            class="form-label">

                            Buscar

                        </label>

                        <input
                            type="text"
                            name="buscar"
                            id="buscar"
                            value="{{ request('buscar') }}"
                            class="form-control"
                            placeholder="Nombre, SKU o código de barras"
                            autocomplete="off">

                    </div>


                    {{-- Categoría --}}

                    <div class="col-lg-2">

                        <label
                            for="categoria"
                            class="form-label">

                            Categoría

                        </label>

                        <select
                            name="categoria"
                            id="categoria"
                            class="form-select">

                            <option value="">
                                Todas
                            </option>

                            @foreach($categories ?? [] as $category)

                                <option
                                    value="{{ $category->id }}"
                                    @selected(request('categoria') == $category->id)>

                                    {{ $category->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Marca --}}

                    <div class="col-lg-2">

                        <label
                            for="marca"
                            class="form-label">

                            Marca

                        </label>

                        <select
                            name="marca"
                            id="marca"
                            class="form-select">

                            <option value="">
                                Todas
                            </option>

                            @foreach($brands ?? [] as $brand)

                                <option
                                    value="{{ $brand->id }}"
                                    @selected(request('marca') == $brand->id)>

                                    {{ $brand->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Estado --}}

                    <div class="col-lg-2">

                        <label
                            for="estado"
                            class="form-label">

                            Estado

                        </label>

                        <select
                            name="estado"
                            id="estado"
                            class="form-select">

                            <option value="">
                                Todos
                            </option>

                            <option
                                value="1"
                                @selected(request('estado') === '1')>

                                Activos

                            </option>

                            <option
                                value="0"
                                @selected(request('estado') === '0')>

                                Inactivos

                            </option>

                        </select>

                    </div>


                    {{-- Buscar --}}

                    <div class="col-lg-2">

                        <button
                            type="submit"
                            class="btn btn-dark w-100">

                            <i class="bi bi-search me-2"></i>

                            Buscar

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- =====================================================
         TABLA
    ====================================================== --}}

    <div class="card products-table-card">

        <div class="card-body p-0">

            <div class="table-responsive products-table-wrapper">

                <table class="table table-hover align-middle mb-0">

                    <thead>

                        <tr>

                            <th class="ps-4">
                                Imagen
                            </th>

                            <th>
                                Producto
                            </th>

                            <th>
                                Categoría
                            </th>

                            <th>
                                Marca
                            </th>

                            <th>
                                Tipo
                            </th>

                            <th>
                                Precio
                            </th>

                            <th>
                                Stock
                            </th>

                            <th>
                                Estado
                            </th>

                            <th class="text-end pe-4">
                                Acciones
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($products as $product)

                            <tr>

                                {{-- =================================================
                                     IMAGEN
                                ================================================== --}}

                                <td class="ps-4">

                                    @if($product->image)

                                        <img
                                            src="{{ asset('storage/' . $product->image) }}"
                                            alt="{{ $product->name }}"
                                            class="product-image">

                                    @else

                                        <div class="product-image-placeholder">

                                            <i class="bi bi-image"></i>

                                        </div>

                                    @endif

                                </td>


                                {{-- =================================================
                                     PRODUCTO
                                ================================================== --}}

                                <td>

                                    <div class="product-name">

                                        {{ $product->name }}

                                    </div>

                                    <small class="text-muted">

                                        SKU: {{ $product->sku }}

                                    </small>

                                    @if($product->barcode)

                                        <small class="d-block text-muted">

                                            {{ $product->barcode }}

                                        </small>

                                    @endif

                                </td>


                                {{-- =================================================
                                     CATEGORÍA
                                ================================================== --}}

                                <td>

                                    {{ $product->category?->name ?? '-' }}

                                </td>


                                {{-- =================================================
                                     MARCA
                                ================================================== --}}

                                <td>

                                    {{ $product->brand?->name ?? '-' }}

                                </td>


                                {{-- =================================================
                                     TIPO
                                ================================================== --}}

                                <td>

                                    {{ $product->tipoConsumo?->nombre ?? '-' }}

                                </td>


                                {{-- =================================================
                                     PRECIO
                                ================================================== --}}

                                <td>

                                    <span class="product-price">

                                        {{ $product->precio_formateado }}

                                    </span>

                                </td>


                                {{-- =================================================
                                     STOCK
                                ================================================== --}}

                                <td>

                                    <span class="badge bg-{{ $product->stock_badge }}">

                                        {{ $product->stock }}

                                    </span>

                                </td>


                                {{-- =================================================
                                     ESTADO
                                ================================================== --}}

                                <td>

                                    @if($product->status)

                                        <span class="badge bg-success">

                                            Activo

                                        </span>

                                    @else

                                        <span class="badge bg-danger">

                                            Inactivo

                                        </span>

                                    @endif

                                </td>


                                {{-- =================================================
                                     ACCIONES
                                ================================================== --}}

                                <td class="text-end pe-4">

                                    <div class="d-flex justify-content-end gap-1">
                                    <div class="admin-actions">
                                        <a
                                            href="{{ route('admin.products.edit', $product) }}"
                                            class="admin-action admin-action-edit"
                                            title="Editar producto">

                                            <i class="bi bi-pencil-square"></i>

                                        </a>


                                        <form
                                            action="{{ route('admin.products.destroy', $product) }}"
                                            method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('¿Eliminar este producto?')">

                                            @csrf

                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="admin-action admin-action-delete"
                                                title="Eliminar producto">

                                                <i class="bi bi-trash"></i>

                                            </button>

                                        </form>

                                    </div>
                                    </div>
                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="9"
                                    class="text-center py-5">

                                    <i class="bi bi-box-seam product-empty-icon"></i>

                                    <h6 class="mt-3 mb-1">

                                        No hay productos

                                    </h6>

                                    <small class="text-muted">

                                        No existen productos registrados.

                                    </small>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- =====================================================
             PAGINACIÓN
        ====================================================== --}}

       @if($products->hasPages())

        <div class="admin-list-pagination">

            {{ $products->onEachSide(1)->links('vendor.pagination.paginacion-admin') }}

        </div>

    @endif

    </div>

</div>

@endsection