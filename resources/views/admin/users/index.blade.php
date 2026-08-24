@extends('layouts.admin')

@section('title', 'Usuarios | PROCÁFES')

@section('content')

<div class="admin-list-page">

    {{-- =====================================================
         ENCABEZADO
    ====================================================== --}}

    <div class="admin-list-header">

        <div class="admin-list-heading">

            <div class="admin-list-heading-icon">
                <i class="bi bi-people-fill"></i>
            </div>

            <div>

                <h1 class="admin-list-title">
                    Usuarios
                </h1>

                <p class="admin-list-subtitle">
                    Administra los usuarios registrados en PROCÁFES.
                </p>

            </div>

        </div>


        <a
            href="{{ route('admin.users.create') }}"
            class="admin-list-new"
        >

            <i class="bi bi-plus-circle"></i>

            Nuevo usuario

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
         TABLA
    ====================================================== --}}

    <div class="admin-list-card">

        <div class="admin-list-table-wrapper">

            <table class="admin-list-table">

                <thead>

                    <tr>

                        <th>
                            Usuario
                        </th>

                        <th>
                            Correo
                        </th>

                        <th>
                            Celular
                        </th>

                        <th>
                            Documento
                        </th>

                        <th>
                            Rol
                        </th>

                        <th>
                            Estado
                        </th>

                        <th>
                            Verificado
                        </th>

                        <th class="admin-list-actions-column">
                            Acciones
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($users as $user)

                        <tr>

                            {{-- =================================================
                                 USUARIO
                            ================================================== --}}

                            <td>

                                <div class="admin-list-name">

                                    <div class="admin-list-icon">

                                        <i class="bi bi-person-fill"></i>

                                    </div>

                                    <strong>
                                        {{ $user->nombre_completo }}
                                    </strong>

                                </div>

                            </td>


                            {{-- =================================================
                                 CORREO
                            ================================================== --}}

                            <td>

                                {{ $user->email }}

                            </td>


                            {{-- =================================================
                                 CELULAR
                            ================================================== --}}

                            <td>

                                {{ $user->celular ?? '—' }}

                            </td>


                            {{-- =================================================
                                 DOCUMENTO
                            ================================================== --}}

                            <td>

                                <strong>
                                    {{ strtoupper($user->tipo_documento) }}
                                </strong>

                                <br>

                                <small>
                                    {{ $user->numero_documento }}
                                </small>

                            </td>


                            {{-- =================================================
                                 ROL
                            ================================================== --}}

                            <td>

                                <span class="admin-list-status">

                                    {{ $user->role?->nombre ?? 'Sin rol' }}

                                </span>

                            </td>


                            {{-- =================================================
                                 ESTADO
                            ================================================== --}}

                            <td>

                                @if($user->estado)

                                    <span class="admin-list-status admin-list-status-success">

                                        Activo

                                    </span>

                                @else

                                    <span class="admin-list-status admin-list-status-danger">

                                        Inactivo

                                    </span>

                                @endif

                            </td>


                            {{-- =================================================
                                 VERIFICADO
                            ================================================== --}}

                            <td>

                                @if($user->email_verified_at)

                                    <span class="admin-list-status admin-list-status-success">

                                        Sí

                                    </span>

                                @else

                                    <span class="admin-list-status admin-list-status-warning">

                                        No

                                    </span>

                                @endif

                            </td>


                            {{-- =================================================
                                 ACCIONES
                            ================================================== --}}

                            <td class="admin-list-actions">

                                <div class="admin-actions">

                                    {{-- =================================================
                                         EDITAR
                                    ================================================== --}}

                                    <a
                                        href="{{ route(
                                            'admin.users.edit',
                                            $user
                                        ) }}"
                                        class="admin-action admin-action-edit"
                                        title="Editar usuario"
                                    >

                                        <i class="bi bi-pencil-square"></i>

                                        <span>
                                            Editar
                                        </span>

                                    </a>


                                    {{-- =================================================
                                         ACTIVAR / DESACTIVAR
                                    ================================================== --}}

                                    <form
                                        action="{{ route(
                                            'admin.users.toggle-status',
                                            $user
                                        ) }}"
                                        method="POST"
                                        class="d-inline"
                                    >

                                        @csrf

                                        @method('PATCH')


                                        @if($user->estado)

                                            {{-- =====================================
                                                 DESACTIVAR
                                            ====================================== --}}

                                            <button
                                                type="submit"
                                                class="admin-action admin-action-deactivate"
                                                title="Desactivar usuario"
                                                onclick="return confirm(
                                                    '¿Deseas desactivar este usuario?'
                                                )"
                                            >

                                                <i class="bi bi-toggle-on"></i>

                                                <span>
                                                    Desactivar
                                                </span>

                                            </button>

                                        @else

                                            {{-- =====================================
                                                 ACTIVAR
                                            ====================================== --}}

                                            <button
                                                type="submit"
                                                class="admin-action admin-action-activate"
                                                title="Activar usuario"
                                                onclick="return confirm(
                                                    '¿Deseas activar este usuario?'
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

                        <tr>

                            <td
                                colspan="8"
                                class="admin-list-empty"
                            >

                                <div class="admin-list-empty-icon">

                                    <i class="bi bi-people"></i>

                                </div>

                                <strong>
                                    No existen usuarios registrados
                                </strong>

                                <span>
                                    Registra un usuario para comenzar a
                                    administrar las cuentas de PROCÁFES.
                                </span>

                                <a
                                    href="{{ route('admin.users.create') }}"
                                    class="admin-list-empty-btn"
                                >

                                    <i class="bi bi-plus-circle"></i>

                                    Crear usuario

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

        @if($users->hasPages())

            <div class="admin-list-pagination">

                {{ $users
                    ->onEachSide(1)
                    ->links('vendor.pagination.paginacion-admin')
                }}

            </div>

        @endif

    </div>

</div>

@endsection