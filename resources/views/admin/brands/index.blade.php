@extends('layouts.admin')

@section('title', 'Marcas | PROCÁFES')

@section('content')

<div class="admin-list-page">

    {{-- =====================================================
         ENCABEZADO
    ====================================================== --}}

    <div class="admin-list-header">

        <div class="admin-list-heading">

            <div class="admin-list-heading-icon">
                <i class="bi bi-award-fill"></i>
            </div>

            <div>

                <h1 class="admin-list-title">
                    Marcas
                </h1>

                <p class="admin-list-subtitle">
                    Administra las marcas registradas en PROCÁFES.
                </p>

            </div>

        </div>


        <a
            href="{{ route('admin.brands.create') }}"
            class="admin-list-new"
        >

            <i class="bi bi-plus-circle"></i>

            Nueva marca

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
                            Marca
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

                    @forelse($brands as $brand)

                        <tr>

                            {{-- MARCA --}}

                            <td>

                                <div class="admin-list-name">

                                    <div class="admin-list-icon">

                                        <i class="bi bi-award-fill"></i>

                                    </div>

                                    <strong>
                                        {{ $brand->name }}
                                    </strong>

                                </div>

                            </td>


                            {{-- DESCRIPCIÓN --}}

                            <td>

                                <span class="admin-list-description">

                                    {{ \Illuminate\Support\Str::limit(
                                        $brand->description,
                                        80
                                    ) }}

                                </span>

                            </td>


                            {{-- ACCIONES --}}

                            <td class="admin-list-actions">

                                <div class="admin-actions">

                                    <a
                                        href="{{ route(
                                            'admin.brands.edit',
                                            $brand
                                        ) }}"
                                        class="admin-action admin-action-edit"
                                        title="Editar marca"
                                    >

                                        <i class="bi bi-pencil-square"></i>

                                    </a>


                                    <form
                                        action="{{ route(
                                            'admin.brands.destroy',
                                            $brand
                                        ) }}"
                                        method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm(
                                            '¿Eliminar esta marca?'
                                        )"
                                    >

                                        @csrf

                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="admin-action admin-action-delete"
                                            title="Eliminar marca"
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

                                    <i class="bi bi-award"></i>

                                </div>

                                <strong>
                                    No existen marcas registradas
                                </strong>

                                <span>
                                    Crea una marca para comenzar a organizar
                                    tus productos.
                                </span>

                                <a
                                    href="{{ route(
                                        'admin.brands.create'
                                    ) }}"
                                    class="admin-list-empty-btn"
                                >

                                    <i class="bi bi-plus-circle"></i>

                                    Crear marca

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

        @if(method_exists($brands, 'links'))

            <div class="admin-list-pagination">

                {{ $brands->onEachSide(1)->links() }}

            </div>

        @endif

    </div>

</div>

@endsection