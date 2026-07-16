@extends('layouts.admin')

@section('title', 'Productos')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h2 class="fw-bold mb-1">

            Productos

        </h2>

        <p class="text-muted mb-0">

            Administra el catálogo de productos de PROCAFES.

        </p>

    </div>

    <a
        href="{{ route('admin.products.create') }}"
        class="btn btn-warning rounded-3 px-4">

        <i class="bi bi-plus-circle me-2"></i>

        Nuevo Producto

    </a>

</div>

{{-- Mensajes --}}

@if(session('success'))

    <div class="alert alert-success alert-dismissible fade show">

        <i class="bi bi-check-circle-fill me-2"></i>

        {{ session('success') }}

        <button
            class="btn-close"
            data-bs-dismiss="alert">
        </button>

    </div>

@endif

@if(session('error'))

    <div class="alert alert-danger alert-dismissible fade show">

        <i class="bi bi-exclamation-triangle-fill me-2"></i>

        {{ session('error') }}

        <button
            class="btn-close"
            data-bs-dismiss="alert">
        </button>

    </div>

@endif

<div class="card border-0 shadow-sm rounded-4">

    <div class="card-body">

        <form
            method="GET"
            action="{{ route('admin.products.index') }}">

            <div class="row g-3 mb-4">

                {{-- Buscar --}}
                <div class="col-lg-4">

                    <label class="form-label">

                        Buscar

                    </label>

                    <input
                        type="text"
                        name="buscar"
                        value="{{ request('buscar') }}"
                        class="form-control"
                        placeholder="Nombre, SKU o código de barras">

                </div>

                {{-- Categoría --}}
                <div class="col-lg-2">

                    <label class="form-label">

                        Categoría

                    </label>

                    <select
                        name="categoria"
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

                    <label class="form-label">

                        Marca

                    </label>

                    <select
                        name="marca"
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

                    <label class="form-label">

                        Estado

                    </label>

                    <select
                        name="estado"
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

                {{-- Botones --}}
                <div class="col-lg-2 d-flex align-items-end">

                    <div class="d-grid w-100">

                        <button
                            class="btn btn-dark">

                            <i class="bi bi-search me-2"></i>

                            Buscar

                        </button>

                    </div>

                </div>

            </div>

        </form>

        <div class="table-responsive">

        <table class="table table-hover align-middle">

    <thead class="table-light">

        <tr>

            <th width="80">

                Imagen

            </th>

            <th>

                Producto

            </th>

            <th>
                Descripción
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

                Precio Venta

            </th>

            <th>

                Stock

            </th>

            <th>

                Estado

            </th>

            <th class="text-end">

                Acciones

            </th>

        </tr>

    </thead>

    <tbody>

        @forelse($products as $product)

            <tr>

                {{-- Imagen --}}
                <td>

                  @if($product->image)

                      <img
                          src="{{ asset('storage/' . $product->image) }}"
                          alt="{{ $product->name }}"
                          class="rounded"
                          style="width:60px;height:60px;object-fit:cover;">

                  @else

                      <div class="bg-light d-flex align-items-center justify-content-center rounded"
                          style="width:60px;height:60px;">

                          <i class="bi bi-image text-muted"></i>

                      </div>

                  @endif

              </td>

                {{-- Producto --}}
                <td>

                    <div class="fw-semibold">

                        {{ $product->name }}

                    </div>

                    <small class="text-muted">

                        SKU:

                        {{ $product->sku }}

                    </small>

                    @if($product->barcode)

                        <br>

                        <small class="text-muted">

                            Código:

                            {{ $product->barcode }}

                        </small>

                    @endif

                </td>

                <td style="max-width:250px;">
                    <small class="text-muted">
                        {{ \Illuminate\Support\Str::limit($product->description, 80) }}
                    </small>
                </td>

                {{-- Categoría --}}
                <td>

                    {{ $product->category?->name ?? '-' }}

                </td>

                {{-- Marca --}}
                <td>

                    {{ $product->brand?->name ?? '-' }}

                </td>

                {{-- Tipo consumo --}}
                <td>

                    {{ $product->tipoConsumo?->nombre ?? '-' }}

                </td>

                {{-- Precio --}}
                <td>

                    <span class="fw-bold text-success">

                        {{ $product->precio_formateado }}

                    </span>

                    <br>

                    <small class="text-muted">

                        Costo:

                        S/

                        {{ number_format($product->cost_price,2) }}

                    </small>

                </td>

                {{-- Stock --}}
                <td>

                    <span

                        class="badge bg-{{ $product->stock_badge }}">

                        {{ $product->stock }}

                    </span>

                    <br>

                    <small class="text-muted">

                        {{ $product->stock_status }}

                    </small>

                </td>

                {{-- Estado --}}
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

                {{-- Acciones --}}
                <td class="text-end">

                    <a

                        href="{{ route('admin.products.edit',$product) }}"

                        class="btn btn-sm btn-outline-primary">

                        <i class="bi bi-pencil-square"></i>

                    </a>

                    <form

                        action="{{ route('admin.products.destroy',$product) }}"

                        method="POST"

                        class="d-inline"

                        onsubmit="return confirm('¿Eliminar este producto?')">

                        @csrf

                        @method('DELETE')

                        <button

                            class="btn btn-sm btn-outline-danger">

                            <i class="bi bi-trash"></i>

                        </button>

                    </form>

                </td>

            </tr>

        @empty

            <tr>

                <td

                    colspan="9"

                    class="text-center py-5">

                    <i

                        class="bi bi-box-seam"

                        style="font-size:50px;"></i>

                    <br><br>

                    No existen productos registrados.

                </td>

            </tr>

        @endforelse

    </tbody>

</table>

        </div>

        @if($products->hasPages())

            <div class="d-flex justify-content-center mt-4">

                {{ $products->withQueryString()->links() }}

            </div>

        @endif

    </div>

</div>

@endsection


@push('styles')

<style>

.table>tbody>tr:hover{

    background:#faf7ea;

    transition:.25s;

}

.table td{

    vertical-align:middle;

}

.table thead th{

    background:#f8f9fa;

    color:#3E350E;

    font-weight:600;

    border-bottom:2px solid #F2DD6C;

}

.badge{

    font-size:.78rem;

    padding:.45rem .7rem;

}

.btn-outline-primary{

    border-radius:10px;

}

.btn-outline-danger{

    border-radius:10px;

}

.card{

    border-radius:18px;

}

.form-control,

.form-select{

    border-radius:12px;

}

.btn-warning{

    background:#DAAD29;

    border:none;

    color:white;

}

.btn-warning:hover{

    background:#C89C1C;

    color:white;

}

img{

    border:1px solid #eee;

}

.pagination{

    margin-bottom:0;

}

.page-link{

    color:#794515;

}

.page-item.active .page-link{

    background:#DAAD29;

    border-color:#DAAD29;

}

</style>

@endpush