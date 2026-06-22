@extends('layouts.auth')

@section('title', 'Recuperar contraseña | PROCAFES')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-md-7 col-lg-5">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4 p-md-5">
                <h1 class="h3 text-center mb-2">¿Olvidaste tu contraseña?</h1>

                <p class="text-muted text-center mb-4">
                    Ingresa tu correo y te enviaremos un enlace para crear una nueva contraseña.
                </p>

                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label">Correo electrónico</label>

                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            class="form-control @error('email') is-invalid @enderror"
                            required
                            autofocus
                            autocomplete="email"
                        >

                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-dark w-100">
                        Enviar enlace de recuperación
                    </button>
                </form>

                <p class="text-center mt-4 mb-0">
                    <a href="{{ route('login') }}">Volver a iniciar sesión</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection