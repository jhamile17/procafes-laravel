@extends('layouts.admin')

@section('title', 'Configuración de la empresa')

@section('content')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/configuracion.css') }}">
@endpush
<div class="config-page">

    {{--=========================================
        ENCABEZADO
    ==========================================--}}

    <div class="config-header">

        <div>

            <h1 class="config-title">
                Configuración de la empresa
            </h1>

            <p class="config-subtitle">
                Administra la información institucional utilizada en la tienda y en la facturación electrónica.
            </p>

        </div>

    </div>

    <form
        action="{{ route('admin.configuracion.update') }}"
        method="POST"
        enctype="multipart/form-data"
    >

        @csrf
        @method('PUT')

        {{--=========================================
            DATOS DE EMPRESA
        ==========================================--}}

        <div class="config-card">

            <div class="config-card-header">

                <h3 class="config-card-title">
                    Datos de la empresa
                </h3>

            </div>

            <div class="config-card-body">

                <div class="row g-4">

                    <div class="col-md-6">

                        <label class="form-label">
                            Nombre de la empresa
                        </label>

                        <input
                            type="text"
                            name="nombre_empresa"
                            class="form-control"
                            value="{{ old('nombre_empresa', $configuracion->nombre_empresa) }}"
                        >

                        @error('nombre_empresa')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror

                    </div>

                    <div class="col-md-6">

                        <label class="form-label">
                            RUC
                        </label>

                        <input
                            type="text"
                            name="ruc"
                            class="form-control"
                            value="{{ old('ruc', $configuracion->ruc) }}"
                        >

                        @error('ruc')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror

                    </div>

                    <div class="col-md-6">

                        <label class="form-label">
                            Correo electrónico
                        </label>

                        <input
                            type="email"
                            name="correo"
                            class="form-control"
                            value="{{ old('correo', $configuracion->correo) }}"
                        >

                        @error('correo')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror

                    </div>

                    <div class="col-md-6">

                        <label class="form-label">
                            Teléfono
                        </label>

                        <input
                            type="text"
                            name="telefono"
                            class="form-control"
                            value="{{ old('telefono', $configuracion->telefono) }}"
                        >

                        @error('telefono')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror

                    </div>

                    <div class="col-12">

                        <label class="form-label">
                            Dirección fiscal
                        </label>

                        <input
                            type="text"
                            name="direccion"
                            class="form-control"
                            value="{{ old('direccion', $configuracion->direccion) }}"
                        >

                        @error('direccion')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror

                    </div>

                </div>

            </div>

        </div>

        {{--=========================================
            REDES
        ==========================================--}}

        <div class="config-card mt-4">

            <div class="config-card-header">

                <h3 class="config-card-title">
                    Redes sociales
                </h3>

            </div>

            <div class="config-card-body">

                <div class="row g-4">

                    <div class="col-md-4">

                        <label class="form-label">
                            Facebook
                        </label>

                        <input
                            type="url"
                            name="facebook"
                            class="form-control"
                            value="{{ old('facebook', $configuracion->facebook) }}"
                        >

                    </div>

                    <div class="col-md-4">

                        <label class="form-label">
                            Instagram
                        </label>

                        <input
                            type="url"
                            name="instagram"
                            class="form-control"
                            value="{{ old('instagram', $configuracion->instagram) }}"
                        >

                    </div>

                    <div class="col-md-4">

                        <label class="form-label">
                            TikTok
                        </label>

                        <input
                            type="url"
                            name="tiktok"
                            class="form-control"
                            value="{{ old('tiktok', $configuracion->tiktok) }}"
                        >

                    </div>

                </div>

            </div>

        </div>

        {{--=========================================
            LOGO
        ==========================================--}}

        <div class="config-card mt-4">

            <div class="config-card-header">

                <h3 class="config-card-title">
                    Logo institucional
                </h3>

            </div>

            <div class="config-card-body">

                <div class="row align-items-center g-4">

                    <div class="col-lg-4">

                        <div class="config-logo">

                            @if($configuracion->logo)

                                <img
                                    src="{{ asset('storage/'.$configuracion->logo) }}"
                                    alt="Logo empresa"
                                >

                            @else

                                <i class="bi bi-building"></i>

                            @endif

                        </div>

                    </div>

                    <div class="col-lg-8">

                        <label class="form-label">
                            Seleccionar nuevo logo
                        </label>

                        <input
                            type="file"
                            name="logo"
                            class="form-control"
                            accept=".jpg,.jpeg,.png,.webp"
                        >

                        @error('logo')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror

                        <small class="text-muted d-block mt-2">
                            Formatos permitidos: JPG, PNG y WEBP (Máximo 2 MB).
                        </small>

                    </div>

                </div>

            </div>

        </div>

        <div class="config-actions">

            <button
                type="submit"
                class="btn-config-save"
            >

                <i class="bi bi-check-circle-fill me-2"></i>

                Guardar configuración

            </button>

        </div>

    </form>

</div>

@endsection