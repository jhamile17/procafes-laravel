@extends('layouts.admin')

@section('title', 'Crear usuario | PROCÁFES')

@section('content')

<div class="admin-form-page">

    {{-- ENCABEZADO --}}
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


    {{-- ERRORES --}}
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


    {{-- FORMULARIO --}}
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


                    {{-- NOMBRE --}}
                    <div class="admin-form-field">

                        <label
                            for="name"
                            class="admin-form-label"
                        >
                            Nombre <span>*</span>
                        </label>

                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            class="admin-form-input @error('name') is-invalid @enderror"
                            required
                        >

                        @error('name')
                            <div class="admin-form-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- CORREO --}}
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
                            required
                        >

                        @error('email')
                            <div class="admin-form-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- TELÉFONO --}}
                    <div class="admin-form-field">

                        <label
                            for="phone"
                            class="admin-form-label"
                        >
                            Teléfono
                        </label>

                        <input
                            type="text"
                            id="phone"
                            name="phone"
                            value="{{ old('phone') }}"
                            class="admin-form-input @error('phone') is-invalid @enderror"
                        >

                        @error('phone')
                            <div class="admin-form-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- TIPO DOCUMENTO --}}
                    <div class="admin-form-field">

                        <label
                            for="document_type"
                            class="admin-form-label"
                        >
                            Tipo de documento <span>*</span>
                        </label>

                        <select
                            id="document_type"
                            name="document_type"
                            class="admin-form-input @error('document_type') is-invalid @enderror"
                            required
                        >

                            <option value="">
                                Seleccionar
                            </option>

                            <option
                                value="dni"
                                @selected(old('document_type') === 'dni')
                            >
                                DNI
                            </option>

                            <option
                                value="ce"
                                @selected(old('document_type') === 'ce')
                            >
                                Carnet de extranjería
                            </option>

                        </select>

                        @error('document_type')
                            <div class="admin-form-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- DOCUMENTO --}}
                    <div class="admin-form-field">

                        <label
                            for="document_number"
                            class="admin-form-label"
                        >
                            Número de documento <span>*</span>
                        </label>

                        <input
                            type="text"
                            id="document_number"
                            name="document_number"
                            value="{{ old('document_number') }}"
                            class="admin-form-input @error('document_number') is-invalid @enderror"
                            required
                        >

                        @error('document_number')
                            <div class="admin-form-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- DIRECCIÓN --}}
                    <div class="admin-form-field">

                        <label
                            for="address"
                            class="admin-form-label"
                        >
                            Dirección
                        </label>

                        <input
                            type="text"
                            id="address"
                            name="address"
                            value="{{ old('address') }}"
                            class="admin-form-input @error('address') is-invalid @enderror"
                        >

                        @error('address')
                            <div class="admin-form-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- ROL --}}
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


                    {{-- CONTRASEÑA --}}
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
                            required
                        >

                        @error('password')
                            <div class="admin-form-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- CONFIRMAR CONTRASEÑA --}}
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
                            required
                        >

                    </div>

                </div>


                {{-- ACCIONES --}}
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