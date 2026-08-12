@extends('layouts.app')

@section('title', 'Editar Perfil')

@section('content')

<div class="customer-dashboard py-5">

    <div class="container">

        <div class="row g-4">

            {{-- Sidebar --}}
            <div class="col-lg-3">

                <x-clienteperfil.sidebar
                    :user="$user" />

            </div>

            {{-- Contenido --}}
            <div class="col-lg-9">

                <div class="customer-content">

                    <x-clienteperfil.header
                        title="Editar Perfil"
                        subtitle="Actualiza la información de tu cuenta." />

                    <div class="customer-card">

                        <div class="customer-card-header">

                            <div>

                                <span class="customer-card-badge">
                                    Información personal
                                </span>

                                <h2 class="customer-card-title">
                                    Editar perfil
                                </h2>

                                <p class="customer-card-subtitle">
                                    Mantén tu información actualizada para agilizar tus compras y entregas.
                                </p>

                            </div>

                        </div>

                        <div class="customer-card-body">

                            <form
                                action="{{ route('customer.profile.update') }}"
                                method="POST">

                                @csrf
                                @method('PUT')

                                <div class="row g-4">

                                    <div class="col-md-6">

                                        <label class="customer-label">
                                            Nombres
                                        </label>

                                        <input
                                            type="text"
                                            name="nombres"
                                            maxlength="100"
                                            required
                                            class="form-control @error('nombres') is-invalid @enderror"
                                            value="{{ old('nombres', $user->nombres) }}"
                                            oninput="this.value=this.value.replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñ\s]/g,'')">
                                        @error('nombres')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror

                                    </div>

                                    <div class="col-md-6">

                                        <label class="customer-label">
                                            Apellido paterno
                                        </label>

                                        <input
                                            type="text"
                                            name="apellido_paterno"
                                            maxlength="100"
                                            required
                                            class="form-control @error('apellido_paterno') is-invalid @enderror"
                                            value="{{ old('apellido_paterno', $user->apellido_paterno) }}"
                                             oninput="this.value=this.value.replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñ\s]/g,'')">
                                        @error('apellido_paterno')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror

                                    </div>

                                    <div class="col-md-6">

                                        <label class="customer-label">
                                            Apellido materno
                                        </label>

                                        <input
                                            type="text"
                                            name="apellido_materno"
                                            maxlength="100"
                                            required
                                            class="form-control @error('apellido_materno') is-invalid @enderror"
                                            value="{{ old('apellido_materno', $user->apellido_materno) }}"
                                             oninput="this.value=this.value.replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñ\s]/g,'')">
                                        @error('apellido_materno')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror

                                    </div>

                                    <div class="col-md-6">

                                        <label class="customer-label">
                                            Celular
                                        </label>

                                        <input
                                            type="text"
                                            name="celular"
                                            maxlength="9"
                                            inputmode="numeric"
                                            required
                                            class="form-control @error('celular') is-invalid @enderror"
                                            value="{{ old('celular', $user->celular) }}">

                                        @error('celular')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror

                                    </div>

                                <div class="d-flex justify-content-end gap-3 mt-5">

                                    <a
                                        href="{{ route('customer.profile') }}"
                                        class="customer-btn-secondary btn-sm">

                                        Cancelar

                                    </a>

                                    <button
                                        type="submit"
                                        class="customer-btn btn-sm py-2">

                                        <i class="bi bi-check-circle me-2"></i>

                                        Guardar cambios

                                    </button>

                                </div>

                            </form>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection