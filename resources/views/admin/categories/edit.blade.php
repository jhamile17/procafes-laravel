@extends('layouts.admin')

@section('title', 'Editar categoría | PROCÁFES')

@section('content')

<div class="admin-form-page">

    {{-- =====================================================
         ENCABEZADO
    ====================================================== --}}

    <div class="admin-form-header">

        <div class="admin-form-heading">

            <div class="admin-form-heading-icon">

                <i class="bi bi-pencil-square"></i>

            </div>

            <div>

                <h1 class="admin-form-title">
                    Editar categoría
                </h1>

                <p class="admin-form-subtitle">
                    Actualiza la información de la categoría.
                </p>

            </div>

        </div>


        <a
            href="{{ route('admin.categories.index') }}"
            class="admin-form-btn admin-form-btn-save"
        >

            <i class="bi bi-arrow-left"></i>

            Volver a categorías

        </a>

    </div>


    {{-- =====================================================
         FORMULARIO
    ====================================================== --}}

    <form
        action="{{ route('admin.categories.update', $category) }}"
        method="POST"
        class="admin-form"
    >

        @csrf
        @method('PUT')


        <section class="admin-form-card">

            {{-- ENCABEZADO DE LA TARJETA --}}

            <div class="admin-form-card-header">

                <div class="admin-form-section-icon">

                    <i class="bi bi-grid-fill"></i>

                </div>

                <div>

                    <h2 class="admin-form-card-title">
                        Información de la categoría
                    </h2>

                    <p class="admin-form-card-subtitle">
                        Modifica los datos principales de la categoría.
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
                            value="{{ old('name', $category->name) }}"
                            class="admin-form-input @error('name') is-invalid @enderror"
                            placeholder="Ej. Cafés calientes"
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
                            placeholder="Describe brevemente la categoría..."
                        >{{ old('description', $category->description) }}</textarea>

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
                href="{{ route('admin.categories.index') }}"
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

                Actualizar categoría

            </button>

        </div>

    </form>

</div>

@endsection