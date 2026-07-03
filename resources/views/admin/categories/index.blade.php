@extends('layouts.admin')

@section('title', 'Categorías')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h2 class="fw-bold mb-1">

            Categorías

        </h2>

        <p class="text-muted mb-0">

            Administra las categorías de los productos de PROCÁFES.

        </p>

    </div>

    <a
        href="{{ route('admin.categories.create') }}"
        class="btn btn-warning">

        <i class="bi bi-plus-circle me-2"></i>

        Nueva categoría

    </a>

</div>

@if(session('success'))

    <div class="alert alert-success">

        <i class="bi bi-check-circle-fill me-2"></i>

        {{ session('success') }}

    </div>

@endif

@if(session('error'))

    <div class="alert alert-danger">

        <i class="bi bi-exclamation-triangle-fill me-2"></i>

        {{ session('error') }}

    </div>

@endif

<div class="card">

    <div class="card-body p-0">

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead>

                    <tr>

                        <th>

                            Nombre

                        </th>

                        <th>

                            Descripción

                        </th>

                        <th width="160"
                            class="text-end">

                            Acciones

                        </th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($categories as $category)

                        <tr>

                            <td>

                                <strong>

                                    {{ $category->name }}

                                </strong>

                            </td>

                            <td>

                                {{ \Illuminate\Support\Str::limit($category->description,70) }}

                            </td>

                            <td class="text-end">

                                <a
                                    href="{{ route('admin.categories.edit',$category) }}"
                                    class="btn btn-sm btn-outline-primary">

                                    <i class="bi bi-pencil-square"></i>

                                </a>

                                <form
                                    action="{{ route('admin.categories.destroy',$category) }}"
                                    method="POST"
                                    class="d-inline"
                                    onsubmit="return confirm('¿Eliminar esta categoría?')">

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
                                colspan="3"
                                class="text-center py-5">

                                <i
                                    class="bi bi-tags"
                                    style="font-size:55px;"></i>

                                <br><br>

                                No existen categorías registradas.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

    @if(method_exists($categories,'links'))

        <div class="card-footer bg-white">

            {{ $categories->onEachSide(1)->links() }}

        </div>

    @endif

</div>

@endsection