@extends('layouts.admin')

@section('title', 'Configuración de la empresa')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/form.css') }}">
@endpush

@section('content')

@php
    $dias = [
        'lunes' => 'Lunes',
        'martes' => 'Martes',
        'miercoles' => 'Miércoles',
        'jueves' => 'Jueves',
        'viernes' => 'Viernes',
        'sabado' => 'Sábado',
        'domingo' => 'Domingo',
    ];

    $horarios = $configuracion->horarios->keyBy('dia');
@endphp


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
                    Administra la información institucional,
                    el horario de atención y las redes sociales de PROCÁFES.
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
                border-color: rgba(24,165,88,.15);
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


                    {{-- NOMBRE --}}

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
                            value="{{ $configuracion->nombre_empresa }}"
                            class="admin-form-input"
                            readonly
                        >

                        <div class="admin-form-help">

                            <i class="bi bi-lock-fill"></i>

                            Dato institucional no modificable.

                        </div>

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
                            value="{{ $configuracion->ruc }}"
                            class="admin-form-input"
                            readonly
                        >

                        <div class="admin-form-help">

                            <i class="bi bi-lock-fill"></i>

                            Dato fiscal no modificable.

                        </div>

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
            HORARIO DE ATENCIÓN
        ================================================== --}}

        <div class="admin-form-card">

            <div class="admin-form-card-header">

                <div class="admin-form-section-icon admin-form-section-icon-gold">

                    <i class="bi bi-clock"></i>

                </div>

                <div>

                    <h3 class="admin-form-card-title">
                        Horario de atención
                    </h3>

                    <p class="admin-form-card-subtitle">
                        Configura los días y horarios en los que PROCÁFES atiende pedidos.
                    </p>

                </div>

            </div>


            <div class="admin-form-card-body">

                <div class="admin-horarios">


                    {{-- =========================================
                        DÍAS DE LA SEMANA
                    ========================================== --}}

                    @foreach($dias as $dia => $nombreDia)

                        @php

                            $horario = $horarios->get($dia);

                            $valorActivo = old(
                                "horarios.$dia.activo",
                                null
                            );

                            $activo = $valorActivo !== null
                                ? $valorActivo === '1'
                                : (bool) ($horario?->activo ?? true);

                            $apertura = old(
                                "horarios.$dia.hora_apertura",
                                $horario?->hora_apertura
                                    ? \Carbon\Carbon::parse(
                                        $horario->hora_apertura
                                    )->format('H:i')
                                    : '08:00'
                            );

                            $cierre = old(
                                "horarios.$dia.hora_cierre",
                                $horario?->hora_cierre
                                    ? \Carbon\Carbon::parse(
                                        $horario->hora_cierre
                                    )->format('H:i')
                                    : '23:00'
                            );

                        @endphp


                        <div class="admin-horario-row">


                            {{-- =================================
                                DÍA
                            ================================== --}}

                            <div class="admin-horario-day">

                                <div class="admin-horario-day-icon">

                                    <i class="bi bi-calendar-day"></i>

                                </div>

                                <strong>
                                    {{ $nombreDia }}
                                </strong>

                            </div>


                            {{-- =================================
                                ESTADO
                            ================================== --}}

                            <div class="admin-horario-status">

                                <label class="admin-horario-switch">

                                    {{-- IMPORTANTE:
                                         Cuando el switch está apagado,
                                         este valor envía 0.
                                    --}}

                                    <input
                                        type="hidden"
                                        name="horarios[{{ $dia }}][activo]"
                                        value="0"
                                    >

                                    <input
                                        type="checkbox"
                                        name="horarios[{{ $dia }}][activo]"
                                        value="1"
                                        class="admin-horario-checkbox"
                                        data-dia="{{ $dia }}"
                                        {{ $activo ? 'checked' : '' }}
                                    >

                                    <span class="admin-horario-slider"></span>

                                </label>


                                <span
                                    class="admin-horario-status-text"
                                    data-status="{{ $dia }}"
                                >
                                    {{ $activo ? 'Abierto' : 'Cerrado' }}
                                </span>

                            </div>


                            {{-- =================================
                                APERTURA
                            ================================== --}}

                            <div class="admin-horario-time">

                                <label
                                    for="hora_apertura_{{ $dia }}"
                                    class="admin-form-label"
                                >
                                    Apertura
                                </label>

                                <input
                                    type="time"
                                    id="hora_apertura_{{ $dia }}"
                                    name="horarios[{{ $dia }}][hora_apertura]"
                                    value="{{ $apertura }}"
                                    class="admin-form-input admin-horario-input"
                                >

                            </div>


                            {{-- =================================
                                CIERRE
                            ================================== --}}

                            <div class="admin-horario-time">

                                <label
                                    for="hora_cierre_{{ $dia }}"
                                    class="admin-form-label"
                                >
                                    Cierre
                                </label>

                                <input
                                    type="time"
                                    id="hora_cierre_{{ $dia }}"
                                    name="horarios[{{ $dia }}][hora_cierre]"
                                    value="{{ $cierre }}"
                                    class="admin-form-input admin-horario-input"
                                >

                            </div>

                        </div>


                        {{-- ERROR APERTURA --}}

                        @error("horarios.$dia.hora_apertura")

                            <div class="admin-form-error">
                                {{ $message }}
                            </div>

                        @enderror


                        {{-- ERROR CIERRE --}}

                        @error("horarios.$dia.hora_cierre")

                            <div class="admin-form-error">
                                {{ $message }}
                            </div>

                        @enderror

                    @endforeach


                    {{-- INFORMACIÓN --}}

                    <div class="admin-form-help">

                        <i class="bi bi-info-circle"></i>

                        Los días marcados como
                        <strong>Abierto</strong>
                        estarán disponibles para recibir pedidos
                        dentro del horario indicado.

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


{{-- =====================================================
    JAVASCRIPT
    Cambiar Abierto / Cerrado visualmente
====================================================== --}}

@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    const switches = document.querySelectorAll(
        '.admin-horario-checkbox'
    );

    switches.forEach(function (checkbox) {

        checkbox.addEventListener('change', function () {

            const dia = this.dataset.dia;

            const texto = document.querySelector(
                '[data-status="' + dia + '"]'
            );

            if (!texto) {
                return;
            }

            texto.textContent = this.checked
                ? 'Abierto'
                : 'Cerrado';

        });

    });

});

</script>

@endpush

@endsection