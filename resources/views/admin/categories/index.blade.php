@extends('layouts.admin')

@section('title', 'Categorías | PROCÁFES')

@section('content')

<div class="admin-list-page">

    {{-- =====================================================
         ENCABEZADO
    ====================================================== --}}

    <div class="admin-list-header">

        <div class="admin-list-heading">

            <div class="admin-list-heading-icon">
                <i class="bi bi-grid-fill"></i>
            </div>

            <div>

                <h1 class="admin-list-title">
                    Categorías
                </h1>

                <p class="admin-list-subtitle">
                    Administra las categorías de los productos de PROCÁFES.
                </p>

            </div>

        </div>


        <a
            href="{{ route('admin.categories.create') }}"
            class="admin-list-new"
        >

            <i class="bi bi-plus-circle"></i>

            Nueva categoría

        </a>

    </div>


    {{-- =====================================================
         MENSAJES
    ====================================================== --}}

    @if(session('success'))

        <div class="admin-list-message admin-list-message-success">

            <i class="bi bi-check-circle-fill"></i>

            <span>
                {{ session('success') }}
            </span>

        </div>

    @endif


    @if(session('error'))

        <div class="admin-list-message admin-list-message-error">

            <i class="bi bi-exclamation-triangle-fill"></i>

            <span>
                {{ session('error') }}
            </span>

        </div>

    @endif


    {{-- =====================================================
         LISTADO
    ====================================================== --}}

    <div class="admin-list-card">

        <div class="admin-list-table-wrapper">

            <table class="admin-list-table">

                <thead>

                    <tr>

                        <th>
                            Categoría
                        </th>

                        <th>
                            Descripción
                        </th>

                        <th class="admin-list-actions-column">
                            Acciones
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($categories as $category)

                        <tr>

                            {{-- CATEGORÍA --}}

                            <td>

                                <div class="admin-list-name">

                                    <div class="admin-list-icon">

                                        <i class="bi bi-tag-fill"></i>

                                    </div>

                                    <strong>
                                        {{ $category->name }}
                                    </strong>

                                </div>

                            </td>


                            {{-- DESCRIPCIÓN --}}

                            <td>

                                <span class="admin-list-description">

                                    {{ \Illuminate\Support\Str::limit(
                                        $category->description,
                                        80
                                    ) }}

                                </span>

                            </td>


                            {{-- ACCIONES --}}

                            <td class="admin-list-actions">

                                <div class="admin-actions">

                                    <a
                                        href="{{ route(
                                            'admin.categories.edit',
                                            $category
                                        ) }}"
                                        class="admin-action admin-action-edit"
                                        title="Editar categoría"
                                    >

                                        <i class="bi bi-pencil-square"></i>

                                    </a>


                                    <form
                                        action="{{ route(
                                            'admin.categories.destroy',
                                            $category
                                        ) }}"
                                        method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm(
                                            '¿Eliminar esta categoría?'
                                        )"
                                    >

                                        @csrf

                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="admin-action admin-action-delete"
                                            title="Eliminar categoría"
                                        >

                                            <i class="bi bi-trash3"></i>

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="3"
                                class="admin-list-empty"
                            >

                                <div class="admin-list-empty-icon">

                                    <i class="bi bi-tags"></i>

                                </div>

                                <strong>
                                    No existen categorías registradas
                                </strong>

                                <span>
                                    Crea una categoría para comenzar a organizar
                                    tus productos.
                                </span>

                                <a
                                    href="{{ route(
                                        'admin.categories.create'
                                    ) }}"
                                    class="admin-list-empty-btn"
                                >

                                    <i class="bi bi-plus-circle"></i>

                                    Crear categoría

                                </a>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- =====================================================
             PAGINACIÓN
        ====================================================== --}}

        @if($categories->hasPages())

        <div class="admin-list-pagination">

            {{ $categories->onEachSide(1)->links('vendor.pagination.paginacion') }}

        </div>

    @endif

    </div>

</div>

@endsection