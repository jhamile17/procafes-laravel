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

                        <th>
                            Estado
                        </th>

                        <th class="admin-list-actions-column">
                            Acciones
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($brands as $brand)

                        <tr>

                            {{-- =================================================
                                 MARCA
                            ================================================== --}}

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


                            {{-- =================================================
                                 DESCRIPCIÓN
                            ================================================== --}}

                            <td>

                                <span class="admin-list-description">

                                    {{ \Illuminate\Support\Str::limit(
                                        $brand->description ?? 'Sin descripción',
                                        80
                                    ) }}

                                </span>

                            </td>


                            {{-- =================================================
                                 ESTADO
                            ================================================== --}}

                            <td>

                                @if($brand->status)

                                    <span class="badge bg-success">
                                        Activo
                                    </span>

                                @else

                                    <span class="badge bg-secondary">
                                        Inactivo
                                    </span>

                                @endif

                            </td>


                            {{-- =================================================
                                 ACCIONES
                            ================================================== --}}

                            <td class="admin-list-actions">

                                <div class="admin-actions">

                                    {{-- EDITAR --}}

                                    <a
                                        href="{{ route(
                                            'admin.brands.edit',
                                            $brand
                                        ) }}"
                                        class="admin-action admin-action-edit"
                                        title="Editar marca"
                                    >

                                        <i class="bi bi-pencil-square"></i>

                                        <span>
                                            Editar
                                        </span>

                                    </a>


                                    {{-- ACTIVAR / DESACTIVAR --}}

                                    <form
                                        action="{{ route(
                                            'admin.brands.toggle-status',
                                            $brand
                                        ) }}"
                                        method="POST"
                                        class="d-inline"
                                    >

                                        @csrf

                                        @method('PATCH')


                                        @if($brand->status)

                                            {{-- DESACTIVAR --}}

                                            <button
                                                type="submit"
                                                class="admin-action admin-action-deactivate"
                                                title="Desactivar marca"
                                                onclick="return confirm(
                                                    '¿Deseas desactivar esta marca?'
                                                )"
                                            >

                                                <i class="bi bi-toggle-on"></i>

                                                <span>
                                                    Desactivar
                                                </span>

                                            </button>

                                        @else

                                            {{-- ACTIVAR --}}

                                            <button
                                                type="submit"
                                                class="admin-action admin-action-activate"
                                                title="Activar marca"
                                                onclick="return confirm(
                                                    '¿Deseas activar esta marca?'
                                                )"
                                            >

                                                <i class="bi bi-toggle-off"></i>

                                                <span>
                                                    Activar
                                                </span>

                                            </button>

                                        @endif

                                    </form>

                                </div>

                            </td>

                        </tr>


                    @empty

                        {{-- =================================================
                             LISTADO VACÍO
                        ================================================== --}}

                        <tr>

                            <td
                                colspan="4"
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

        @if($brands->hasPages())

            <div class="admin-list-pagination">

                {{ $brands
                    ->onEachSide(1)
                    ->links('vendor.pagination.paginacion-admin')
                }}

            </div>

        @endif

    </div>

</div>

@endsection