@extends('layouts.admin')

@section('title', 'Configuración de la empresa')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/form.css') }}">
@endpush

@section('content')

<div class="admin-form-page">

    {{-- =====================================================
        ENCABEZADO
    ====================================================== --}}

    <div class="admin-form-header">

        <div class="admin-form-heading">

            <div class="admin-form-heading-icon">
                <i class="bi bi-building"></i>
            </div>

            <div>

                <h1 class="admin-form-title">
                    Configuración de la empresa
                </h1>

                <p class="admin-form-subtitle">
                    Administra la información institucional utilizada
                    en la tienda y en la facturación electrónica.
                </p>

            </div>

        </div>

    </div>


    {{-- =====================================================
        ERRORES DE VALIDACIÓN
    ====================================================== --}}

    @if ($errors->any())

        <div class="admin-form-alert">

            <div class="admin-form-alert-title">

                <i class="bi bi-exclamation-triangle-fill"></i>

                <span>
                    Revisa los siguientes campos
                </span>

            </div>

            <ul class="admin-form-alert-list">

                @foreach ($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- =====================================================
        MENSAJE DE ÉXITO
    ====================================================== --}}

    @if (session('success'))

        <div
            class="admin-form-alert"
            style="
                color: var(--color-success);
                background: var(--color-success-soft);
                border-color: rgba(24, 165, 88, .15);
            "
        >

            <div class="admin-form-alert-title">

                <i class="bi bi-check-circle-fill"></i>

                <span>
                    {{ session('success') }}
                </span>

            </div>

        </div>

    @endif


    {{-- =====================================================
        MENSAJE DE ERROR
    ====================================================== --}}

    @if (session('error'))

        <div class="admin-form-alert">

            <div class="admin-form-alert-title">

                <i class="bi bi-exclamation-triangle-fill"></i>

                <span>
                    {{ session('error') }}
                </span>

            </div>

        </div>

    @endif


    {{-- =====================================================
        FORMULARIO
    ====================================================== --}}

    <form
        action="{{ route('admin.configuracion.update') }}"
        method="POST"
    >

        @csrf
        @method('PUT')


        {{-- =================================================
            DATOS DE LA EMPRESA
        ================================================== --}}

        <div class="admin-form-card">

            <div class="admin-form-card-header">

                <div class="admin-form-section-icon">

                    <i class="bi bi-building"></i>

                </div>

                <div>

                    <h3 class="admin-form-card-title">
                        Datos de la empresa
                    </h3>

                    <p class="admin-form-card-subtitle">
                        Información institucional de PROCÁFES.
                    </p>

                </div>

            </div>


            <div class="admin-form-card-body">

                <div class="admin-form-grid">


                    {{-- NOMBRE DE EMPRESA --}}

                    <div class="admin-form-field admin-form-field-wide">

                        <label
                            for="nombre_empresa"
                            class="admin-form-label"
                        >
                            Nombre de la empresa
                            <span>*</span>
                        </label>

                        <input
                            type="text"
                            id="nombre_empresa"
                            name="nombre_empresa"
                            value="{{ old('nombre_empresa', $configuracion->nombre_empresa) }}"
                            class="admin-form-input @error('nombre_empresa') is-invalid @enderror"
                            placeholder="Nombre de la empresa"
                        >

                        @error('nombre_empresa')

                            <div class="admin-form-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- RUC --}}

                    <div class="admin-form-field">

                        <label
                            for="ruc"
                            class="admin-form-label"
                        >
                            RUC
                            <span>*</span>
                        </label>

                        <input
                            type="text"
                            id="ruc"
                            name="ruc"
                            value="{{ old('ruc', $configuracion->ruc) }}"
                            class="admin-form-input @error('ruc') is-invalid @enderror"
                            placeholder="20123456789"
                            maxlength="11"
                        >

                        @error('ruc')

                            <div class="admin-form-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- CORREO --}}

                    <div class="admin-form-field">

                        <label
                            for="correo"
                            class="admin-form-label"
                        >
                            Correo electrónico
                            <span>*</span>
                        </label>

                        <input
                            type="email"
                            id="correo"
                            name="correo"
                            value="{{ old('correo', $configuracion->correo) }}"
                            class="admin-form-input @error('correo') is-invalid @enderror"
                            placeholder="correo@procafes.com"
                        >

                        @error('correo')

                            <div class="admin-form-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- TELÉFONO --}}

                    <div class="admin-form-field">

                        <label
                            for="telefono"
                            class="admin-form-label"
                        >
                            Teléfono
                        </label>

                        <input
                            type="text"
                            id="telefono"
                            name="telefono"
                            value="{{ old('telefono', $configuracion->telefono) }}"
                            class="admin-form-input @error('telefono') is-invalid @enderror"
                            placeholder="999 999 999"
                        >

                        @error('telefono')

                            <div class="admin-form-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- DIRECCIÓN --}}

                    <div class="admin-form-field-full">

                        <label
                            for="direccion"
                            class="admin-form-label"
                        >
                            Dirección fiscal
                            <span>*</span>
                        </label>

                        <input
                            type="text"
                            id="direccion"
                            name="direccion"
                            value="{{ old('direccion', $configuracion->direccion) }}"
                            class="admin-form-input @error('direccion') is-invalid @enderror"
                            placeholder="Dirección fiscal de la empresa"
                        >

                        @error('direccion')

                            <div class="admin-form-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>

            </div>

        </div>


        {{-- =================================================
            REDES SOCIALES
        ================================================== --}}

        <div class="admin-form-card">

            <div class="admin-form-card-header">

                <div class="admin-form-section-icon admin-form-section-icon-gold">

                    <i class="bi bi-share"></i>

                </div>

                <div>

                    <h3 class="admin-form-card-title">
                        Redes sociales
                    </h3>

                    <p class="admin-form-card-subtitle">
                        Enlaces oficiales de las redes sociales de PROCÁFES.
                    </p>

                </div>

            </div>


            <div class="admin-form-card-body">

                <div class="admin-form-grid">


                    {{-- FACEBOOK --}}

                    <div class="admin-form-field">

                        <label
                            for="facebook"
                            class="admin-form-label"
                        >
                            Facebook
                        </label>

                        <input
                            type="url"
                            id="facebook"
                            name="facebook"
                            value="{{ old('facebook', $configuracion->facebook) }}"
                            class="admin-form-input @error('facebook') is-invalid @enderror"
                            placeholder="https://facebook.com/..."
                        >

                        @error('facebook')

                            <div class="admin-form-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- INSTAGRAM --}}

                    <div class="admin-form-field">

                        <label
                            for="instagram"
                            class="admin-form-label"
                        >
                            Instagram
                        </label>

                        <input
                            type="url"
                            id="instagram"
                            name="instagram"
                            value="{{ old('instagram', $configuracion->instagram) }}"
                            class="admin-form-input @error('instagram') is-invalid @enderror"
                            placeholder="https://instagram.com/..."
                        >

                        @error('instagram')

                            <div class="admin-form-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- TIKTOK --}}

                    <div class="admin-form-field">

                        <label
                            for="tiktok"
                            class="admin-form-label"
                        >
                            TikTok
                        </label>

                        <input
                            type="url"
                            id="tiktok"
                            name="tiktok"
                            value="{{ old('tiktok', $configuracion->tiktok) }}"
                            class="admin-form-input @error('tiktok') is-invalid @enderror"
                            placeholder="https://tiktok.com/@..."
                        >

                        @error('tiktok')

                            <div class="admin-form-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>

            </div>

        </div>


        {{-- =================================================
            ACCIONES
        ================================================== --}}

        <div class="admin-form-actions">

            <a
                href="{{ route('admin.dashboard') }}"
                class="admin-form-btn admin-form-btn-cancel"
            >
                <i class="bi bi-arrow-left"></i>
                Cancelar
            </a>

            <button
                type="submit"
                class="admin-form-btn admin-form-btn-save"
            >
                <i class="bi bi-check-circle-fill"></i>
                Guardar configuración
            </button>

        </div>

    </form>

</div>

@endsection