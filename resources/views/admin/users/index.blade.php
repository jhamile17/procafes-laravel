@extends('layouts.admin')

@section('title', 'Usuarios')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h2 class="fw-bold mb-1">

            Usuarios

        </h2>

        <p class="text-muted mb-0">

            Administra los usuarios registrados en PROCÁFES.

        </p>

    </div>

    <a
        href="{{ route('admin.users.create') }}"
        class="btn btn-warning">

        <i class="bi bi-plus-circle me-2"></i>

        Nuevo Usuario

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

                        <th>Nombre</th>

                        <th>Correo</th>

                        <th>Celular</th>

                        <th>Documento</th>

                        <th>Rol</th>

                        <th>Estado</th>

                        <th>Verificado</th>

                        <th class="text-end">

                            Acciones

                        </th>

                    </tr>

                </thead>

                <tbody>

                @forelse($users as $user)

                    <tr>

                        <td>

                            <strong>

                                {{ $user->nombre_completo }}

                            </strong>

                        </td>

                        <td>

                            {{ $user->email }}

                        </td>

                        <td>

                            {{ $user->celular ?? '-' }}

                        </td>

                        <td>

                            {{ $user->tipo_documento }}

                            <br>

                            <small class="text-muted">

                                {{ $user->numero_documento }}

                            </small>

                        </td>

                        <td>

                            <span class="badge bg-primary">

                                {{ $user->role?->nombre ?? 'Sin rol' }}

                            </span>

                        </td>

                        <td>

                            @if($user->estado)

                                <span class="badge bg-success">

                                    Activo

                                </span>

                            @else

                                <span class="badge bg-danger">

                                    Inactivo

                                </span>

                            @endif

                        </td>

                        <td>

                            @if($user->email_verified_at)

                                <span class="badge bg-success">

                                    Sí

                                </span>

                            @else

                                <span class="badge bg-warning text-dark">

                                    No

                                </span>

                            @endif

                        </td>

                        <td class="text-end">

                            <a
                                href="{{ route('admin.users.edit',$user) }}"
                                class="btn btn-sm btn-outline-primary">

                                <i class="bi bi-pencil-square"></i>

                            </a>

                            <form
                                action="{{ route('admin.users.destroy',$user) }}"
                                method="POST"
                                class="d-inline"
                                onsubmit="return confirm('¿Eliminar este usuario?')">

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
                            colspan="8"
                            class="text-center py-5">

                            <i
                                class="bi bi-people"
                                style="font-size:55px;"></i>

                            <br><br>

                            No existen usuarios registrados.

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>

    @if(method_exists($users,'links'))

        <div class="card-footer bg-white">

            {{ $users->onEachSide(1)->links() }}

        </div>

    @endif

</div>

@endsection