@extends('layouts.admin')

@section('title', 'Nueva marca | PROCÁFES')

@section('content')

<div class="admin-form-page">

    {{-- =====================================================
         ENCABEZADO
    ====================================================== --}}

    <div class="admin-form-header">

        <div class="admin-form-heading">

            <div class="admin-form-heading-icon">

                <i class="bi bi-plus-circle-fill"></i>

            </div>

            <div>

                <h1 class="admin-form-title">
                    Nueva marca
                </h1>

                <p class="admin-form-subtitle">
                    Registra una nueva marca para los productos de PROCÁFES.
                </p>

            </div>

        </div>


        <a
            href="{{ route('admin.brands.index') }}"
            class="admin-form-back"
        >

            <i class="bi bi-arrow-left"></i>

            Volver a marcas

        </a>

    </div>


    {{-- =====================================================
         FORMULARIO
    ====================================================== --}}

    <form
        action="{{ route('admin.brands.store') }}"
        method="POST"
        class="admin-form"
    >

        @csrf


        <section class="admin-form-card">

            {{-- ENCABEZADO --}}

            <div class="admin-form-card-header">

                <div class="admin-form-section-icon">

                    <i class="bi bi-award-fill"></i>

                </div>

                <div>

                    <h2 class="admin-form-card-title">
                        Información de la marca
                    </h2>

                    <p class="admin-form-card-subtitle">
                        Completa los datos principales de la nueva marca.
                    </p>

                </div>

            </div>


            {{-- CONTENIDO --}}

            <div class="admin-form-card-body">

                <div class="admin-form-grid">


                    {{-- NOMBRE --}}

                    <div class="admin-form-field admin-form-field-full">

                        <label
                            for="name"
                            class="admin-form-label"
                        >

                            Nombre
                            <span>*</span>

                        </label>

                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            class="admin-form-input @error('name') is-invalid @enderror"
                            placeholder="Ej. PROCÁFES"
                            autofocus
                        >

                        @error('name')

                            <div class="admin-form-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- DESCRIPCIÓN --}}

                    <div class="admin-form-field admin-form-field-full">

                        <label
                            for="description"
                            class="admin-form-label"
                        >

                            Descripción

                        </label>

                        <textarea
                            id="description"
                            name="description"
                            rows="4"
                            class="admin-form-input @error('description') is-invalid @enderror"
                            placeholder="Describe brevemente la marca..."
                        >{{ old('description') }}</textarea>

                        @error('description')

                            <div class="admin-form-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>

            </div>

        </section>


        {{-- =====================================================
             ACCIONES
        ====================================================== --}}

        <div class="admin-form-actions">

            <a
                href="{{ route('admin.brands.index') }}"
                class="admin-form-btn admin-form-btn-cancel"
            >

                <i class="bi bi-x-lg"></i>

                Cancelar

            </a>


            <button
                type="submit"
                class="admin-form-btn admin-form-btn-save"
            >

                <i class="bi bi-check-circle-fill"></i>

                Guardar marca

            </button>

        </div>

    </form>

</div>

@endsection