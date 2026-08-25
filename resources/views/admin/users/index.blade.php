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
         FILTROS
    ====================================================== --}}

    <div class="admin-users-filter-card">

        <form
            method="GET"
            action="{{ route('admin.users.index') }}"
        >

            <div class="admin-users-filter-grid">


                {{-- =================================================
                     BUSCAR
                ================================================== --}}

                <div class="admin-users-filter-search">

                    <label
                        for="buscar"
                        class="admin-users-filter-label"
                    >
                        Buscar
                    </label>

                    <div class="admin-users-search-wrapper">

                        <i class="bi bi-search"></i>

                        <input
                            type="text"
                            id="buscar"
                            name="buscar"
                            value="{{ request('buscar') }}"
                            class="admin-users-filter-input"
                            placeholder="Nombre, correo o documento"
                            autocomplete="off"
                        >

                    </div>

                </div>


                {{-- =================================================
                     ROL
                ================================================== --}}

                <div class="admin-users-filter-field">

                    <label
                        for="rol"
                        class="admin-users-filter-label"
                    >
                        Rol
                    </label>

                    <select
                        name="rol"
                        id="rol"
                        class="admin-users-filter-input"
                    >

                        <option value="">
                            Todos los roles
                        </option>

                        @foreach($roles as $role)

                            <option
                                value="{{ $role->id }}"
                                @selected(request('rol') == $role->id)
                            >
                                {{ $role->nombre }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- =================================================
                     ESTADO
                ================================================== --}}

                <div class="admin-users-filter-field">

                    <label
                        for="estado"
                        class="admin-users-filter-label"
                    >
                        Estado
                    </label>

                    <select
                        name="estado"
                        id="estado"
                        class="admin-users-filter-input"
                    >

                        <option value="">
                            Todos los estados
                        </option>

                        <option
                            value="1"
                            @selected(request('estado') === '1')
                        >
                            Activos
                        </option>

                        <option
                            value="0"
                            @selected(request('estado') === '0')
                        >
                            Inactivos
                        </option>

                    </select>

                </div>


                {{-- =================================================
                     VERIFICACIÓN
                ================================================== --}}

                <div class="admin-users-filter-field">

                    <label
                        for="verificado"
                        class="admin-users-filter-label"
                    >
                        Verificación
                    </label>

                    <select
                        name="verificado"
                        id="verificado"
                        class="admin-users-filter-input"
                    >

                        <option value="">
                            Todos
                        </option>

                        <option
                            value="1"
                            @selected(request('verificado') === '1')
                        >
                            Verificados
                        </option>

                        <option
                            value="0"
                            @selected(request('verificado') === '0')
                        >
                            No verificados
                        </option>

                    </select>

                </div>


                {{-- =================================================
                     BOTÓN BUSCAR
                ================================================== --}}

                <div class="admin-users-filter-button">

                    <button
                        type="submit"
                        class="admin-users-search-btn"
                    >

                        <i class="bi bi-search"></i>

                        Buscar

                    </button>

                </div>

            </div>

        </form>

    </div>


    {{-- =====================================================
         LISTADO DE USUARIOS
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

                                @if($user->tipo_documento)

                                    <strong>
                                        {{ strtoupper($user->tipo_documento) }}
                                    </strong>

                                    <br>

                                @endif

                                <small>
                                    {{ $user->numero_documento ?? '—' }}
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
                                 VERIFICACIÓN
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


                                    {{-- =========================================
                                         EDITAR ROL
                                    ========================================== --}}

                                    <button
                                        type="button"
                                        class="admin-action admin-action-edit"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editUserModal{{ $user->id }}"
                                        title="Editar permisos"
                                    >

                                        <i class="bi bi-pencil-square"></i>

                                        <span>
                                            Editar
                                        </span>

                                    </button>


                                    {{-- =========================================
                                         ACTIVAR / DESACTIVAR
                                    ========================================== --}}

                                    @if(auth()->id() !== $user->id)

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

                                    @else

                                        <span
                                            class="admin-action admin-action-disabled"
                                            title="Esta es tu cuenta"
                                        >

                                            <i class="bi bi-shield-lock"></i>

                                            <span>
                                                Mi cuenta
                                            </span>

                                        </span>

                                    @endif

                                </div>

                            </td>

                        </tr>


                    @empty

                        {{-- =================================================
                             SIN RESULTADOS
                        ================================================== --}}

                        <tr>

                            <td
                                colspan="8"
                                class="admin-list-empty"
                            >

                                <div class="admin-list-empty-icon">

                                    <i class="bi bi-people"></i>

                                </div>

                                <strong>
                                    No se encontraron usuarios
                                </strong>

                                <span>
                                    No existen usuarios que coincidan
                                    con los filtros seleccionados.
                                </span>

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


    {{-- =====================================================
         MODALES
    ====================================================== --}}

    @foreach($users as $user)

        <div
            class="modal fade"
            id="editUserModal{{ $user->id }}"
            tabindex="-1"
            aria-labelledby="editUserModalLabel{{ $user->id }}"
            aria-hidden="true"
        >

            <div class="modal-dialog modal-dialog-centered">

                <div class="modal-content">


                    {{-- =============================================
                         HEADER
                    ============================================== --}}

                    <div class="modal-header">

                        <div>

                            <h5
                                class="modal-title"
                                id="editUserModalLabel{{ $user->id }}"
                            >

                                <i class="bi bi-shield-lock me-2"></i>

                                Editar permisos

                            </h5>

                            <small class="text-muted">
                                Modifica el nivel de acceso del usuario.
                            </small>

                        </div>

                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Cerrar"
                        ></button>

                    </div>


                    {{-- =============================================
                         FORMULARIO
                    ============================================== --}}

                    <form
                        action="{{ route(
                            'admin.users.update',
                            $user
                        ) }}"
                        method="POST"
                    >

                        @csrf

                        @method('PUT')


                        <div class="modal-body">


                            {{-- USUARIO --}}

                            <div class="mb-3">

                                <label class="form-label fw-semibold">
                                    Usuario
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    value="{{ $user->nombre_completo }}"
                                    readonly
                                >

                            </div>


                            {{-- CORREO --}}

                            <div class="mb-3">

                                <label class="form-label fw-semibold">
                                    Correo electrónico
                                </label>

                                <input
                                    type="email"
                                    class="form-control"
                                    value="{{ $user->email }}"
                                    readonly
                                >

                            </div>


                            {{-- ESTADO --}}

                            <div class="mb-3">

                                <label class="form-label fw-semibold">
                                    Estado actual
                                </label>

                                <div>

                                    @if($user->estado)

                                        <span class="admin-list-status admin-list-status-success">

                                            <i class="bi bi-check-circle me-1"></i>

                                            Activo

                                        </span>

                                    @else

                                        <span class="admin-list-status admin-list-status-danger">

                                            <i class="bi bi-x-circle me-1"></i>

                                            Inactivo

                                        </span>

                                    @endif

                                </div>

                            </div>


                            {{-- ROL --}}

                            <div class="mb-3">

                                <label
                                    for="role_id_{{ $user->id }}"
                                    class="form-label fw-semibold"
                                >

                                    Rol del usuario

                                    <span class="text-danger">
                                        *
                                    </span>

                                </label>

                                <select
                                    id="role_id_{{ $user->id }}"
                                    name="role_id"
                                    class="form-select"
                                    required
                                    @disabled(auth()->id() === $user->id)
                                >

                                    @foreach($roles as $role)

                                        <option
                                            value="{{ $role->id }}"
                                            @selected(
                                                $user->role_id == $role->id
                                            )
                                        >

                                            {{ $role->nombre }}

                                        </option>

                                    @endforeach

                                </select>

                                <div class="form-text">

                                    El rol determina los permisos
                                    de acceso dentro del sistema.

                                </div>

                            </div>


                            {{-- AVISO PROPIO ADMINISTRADOR --}}

                            @if(auth()->id() === $user->id)

                                <div class="alert alert-warning mb-0">

                                    <i class="bi bi-exclamation-triangle me-2"></i>

                                    No puedes modificar tu propio rol.

                                </div>

                            @endif

                        </div>


                        {{-- =============================================
                             FOOTER
                        ============================================== --}}

                        <div class="modal-footer">

                            <button
                                type="button"
                                class="btn btn-light"
                                data-bs-dismiss="modal"
                            >

                                <i class="bi bi-x-lg me-1"></i>

                                Cancelar

                            </button>


                            @if(auth()->id() !== $user->id)

                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                >

                                    <i class="bi bi-check-circle me-1"></i>

                                    Guardar cambios

                                </button>

                            @endif

                        </div>

                    </form>

                </div>

            </div>

        </div>

    @endforeach

</div>

@endsection