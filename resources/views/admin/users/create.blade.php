@extends('layouts.admin')

@section('title', 'Crear usuario | PROCÁFES')

@section('content')

<div class="admin-form-page">

    {{-- =====================================================
         ENCABEZADO
    ====================================================== --}}

    <div class="admin-form-header">

        <div class="admin-form-heading">

            <div class="admin-form-heading-icon">
                <i class="bi bi-person-plus-fill"></i>
            </div>

            <div>

                <h1 class="admin-form-title">
                    Crear usuario
                </h1>

                <p class="admin-form-subtitle">
                    Registra un nuevo usuario en PROCÁFES.
                </p>

            </div>

        </div>

    </div>


    {{-- =====================================================
         ERRORES
    ====================================================== --}}

    @if ($errors->any())

        <div class="admin-form-alert">

            <div class="admin-form-alert-title">

                <i class="bi bi-exclamation-triangle-fill"></i>

                <span>
                    Revisa los datos ingresados.
                </span>

            </div>

            <ul class="admin-form-alert-list">

                @foreach ($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- =====================================================
         FORMULARIO
    ====================================================== --}}

    <div class="admin-form-card">

        <div class="admin-form-card-header">

            <div class="admin-form-section-icon">

                <i class="bi bi-person"></i>

            </div>

            <div>

                <h2 class="admin-form-card-title">
                    Información del usuario
                </h2>

                <p class="admin-form-card-subtitle">
                    Completa los datos principales del usuario.
                </p>

            </div>

        </div>


        <div class="admin-form-card-body">

            <form
                action="{{ route('admin.users.store') }}"
                method="POST"
            >

                @csrf


                <div class="admin-form-grid">


                    {{-- =================================================
                         NOMBRES
                    ================================================== --}}

                    <div class="admin-form-field">

                        <label
                            for="nombres"
                            class="admin-form-label"
                        >
                            Nombres <span>*</span>
                        </label>

                        <input
                            type="text"
                            id="nombres"
                            name="nombres"
                            value="{{ old('nombres') }}"
                            class="admin-form-input @error('nombres') is-invalid @enderror"
                            placeholder="Ej. Sayuri Maylin"
                            autocomplete="given-name"
                            required
                        >

                        @error('nombres')

                            <div class="admin-form-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- =================================================
                         APELLIDO PATERNO
                    ================================================== --}}

                    <div class="admin-form-field">

                        <label
                            for="apellido_paterno"
                            class="admin-form-label"
                        >
                            Apellido paterno <span>*</span>
                        </label>

                        <input
                            type="text"
                            id="apellido_paterno"
                            name="apellido_paterno"
                            value="{{ old('apellido_paterno') }}"
                            class="admin-form-input @error('apellido_paterno') is-invalid @enderror"
                            placeholder="Ej. Damian"
                            autocomplete="family-name"
                            required
                        >

                        @error('apellido_paterno')

                            <div class="admin-form-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- =================================================
                         APELLIDO MATERNO
                    ================================================== --}}

                    <div class="admin-form-field">

                        <label
                            for="apellido_materno"
                            class="admin-form-label"
                        >
                            Apellido materno
                        </label>

                        <input
                            type="text"
                            id="apellido_materno"
                            name="apellido_materno"
                            value="{{ old('apellido_materno') }}"
                            class="admin-form-input @error('apellido_materno') is-invalid @enderror"
                            placeholder="Ej. Rojas"
                        >

                        @error('apellido_materno')

                            <div class="admin-form-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- =================================================
                         CORREO
                    ================================================== --}}

                    <div class="admin-form-field">

                        <label
                            for="email"
                            class="admin-form-label"
                        >
                            Correo electrónico <span>*</span>
                        </label>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            class="admin-form-input @error('email') is-invalid @enderror"
                            placeholder="correo@ejemplo.com"
                            autocomplete="email"
                            required
                        >

                        @error('email')

                            <div class="admin-form-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- =================================================
                         CELULAR
                    ================================================== --}}

                    <div class="admin-form-field">

                        <label
                            for="celular"
                            class="admin-form-label"
                        >
                            Celular
                        </label>

                        <input
                            type="text"
                            id="celular"
                            name="celular"
                            value="{{ old('celular') }}"
                            class="admin-form-input @error('celular') is-invalid @enderror"
                            placeholder="Ej. 987654321"
                            maxlength="20"
                            autocomplete="tel"
                        >

                        @error('celular')

                            <div class="admin-form-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- =================================================
                         TIPO DOCUMENTO
                    ================================================== --}}

                    <div class="admin-form-field">

                        <label
                            for="tipo_documento"
                            class="admin-form-label"
                        >
                            Tipo de documento <span>*</span>
                        </label>

                        <select
                            id="tipo_documento"
                            name="tipo_documento"
                            class="admin-form-input @error('tipo_documento') is-invalid @enderror"
                            required
                        >

                            <option value="">
                                Seleccionar
                            </option>

                            <option
                                value="dni"
                                @selected(old('tipo_documento') === 'dni')
                            >
                                DNI
                            </option>

                            <option
                                value="ce"
                                @selected(old('tipo_documento') === 'ce')
                            >
                                Carnet de extranjería
                            </option>

                        </select>

                        @error('tipo_documento')

                            <div class="admin-form-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- =================================================
                         NÚMERO DOCUMENTO
                    ================================================== --}}

                    <div class="admin-form-field">

                        <label
                            for="numero_documento"
                            class="admin-form-label"
                        >
                            Número de documento <span>*</span>
                        </label>

                        <input
                            type="text"
                            id="numero_documento"
                            name="numero_documento"
                            value="{{ old('numero_documento') }}"
                            class="admin-form-input @error('numero_documento') is-invalid @enderror"
                            placeholder="Ej. 73724412"
                            maxlength="20"
                            required
                        >

                        @error('numero_documento')

                            <div class="admin-form-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- =================================================
                         DIRECCIÓN
                    ================================================== --}}

                    <div class="admin-form-field">

                        <label
                            for="direccion"
                            class="admin-form-label"
                        >
                            Dirección
                        </label>

                        <input
                            type="text"
                            id="direccion"
                            name="direccion"
                            value="{{ old('direccion') }}"
                            class="admin-form-input @error('direccion') is-invalid @enderror"
                            placeholder="Ej. Av. Principal 123"
                            maxlength="255"
                            autocomplete="street-address"
                        >

                        @error('direccion')

                            <div class="admin-form-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- =================================================
                         ROL
                    ================================================== --}}

                    <div class="admin-form-field">

                        <label
                            for="role_id"
                            class="admin-form-label"
                        >
                            Rol <span>*</span>
                        </label>

                        <select
                            id="role_id"
                            name="role_id"
                            class="admin-form-input @error('role_id') is-invalid @enderror"
                            required
                        >

                            <option value="">
                                Seleccionar rol
                            </option>

                            @foreach($roles as $role)

                                <option
                                    value="{{ $role->id }}"
                                    @selected(old('role_id') == $role->id)
                                >
                                    {{ $role->nombre }}
                                </option>

                            @endforeach

                        </select>

                        @error('role_id')

                            <div class="admin-form-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- =================================================
                         CONTRASEÑA
                    ================================================== --}}

                    <div class="admin-form-field">

                        <label
                            for="password"
                            class="admin-form-label"
                        >
                            Contraseña <span>*</span>
                        </label>

                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="admin-form-input @error('password') is-invalid @enderror"
                            placeholder="Mínimo 6 caracteres"
                            autocomplete="new-password"
                            required
                        >

                        @error('password')

                            <div class="admin-form-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- =================================================
                         CONFIRMAR CONTRASEÑA
                    ================================================== --}}

                    <div class="admin-form-field">

                        <label
                            for="password_confirmation"
                            class="admin-form-label"
                        >
                            Confirmar contraseña <span>*</span>
                        </label>

                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            class="admin-form-input"
                            placeholder="Repite la contraseña"
                            autocomplete="new-password"
                            required
                        >

                    </div>


                </div>


                {{-- =================================================
                     ACCIONES
                ================================================== --}}

                <div class="admin-form-actions">

                    <a
                        href="{{ route('admin.users.index') }}"
                        class="admin-form-btn admin-form-btn-cancel"
                    >

                        <i class="bi bi-arrow-left"></i>

                        Cancelar

                    </a>

                    <button
                        type="submit"
                        class="admin-form-btn admin-form-btn-save"
                    >

                        <i class="bi bi-person-plus"></i>

                        Crear usuario

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection