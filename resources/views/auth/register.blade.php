@extends('layouts.auth')

@section('title', 'Crear cuenta | PROCAFES')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-8 col-xl-7">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4 p-md-5">
                <h1 class="h3 text-center mb-2">Crea tu cuenta</h1>
                <p class="text-muted text-center mb-4">
                    Regístrate para comprar y seguir tus pedidos.
                </p>

                <a href="{{ route('auth.google.redirect') }}"
                   class="btn btn-outline-dark w-100 mb-3">
                    Continuar con Google
                </a>

                <div class="text-center text-muted small mb-3">o regístrate con tu correo</div>

                <form method="POST" action="{{ route('register.store') }}">
                    @csrf

                    <div class="row g-3">
                        <div class="col-12">
                            <label for="name" class="form-label">Nombre completo</label>
                            <input id="name" name="name" type="text"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}" required autofocus>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="email" class="form-label">Correo electrónico</label>
                            <input id="email" name="email" type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="phone" class="form-label">Teléfono <span class="text-muted">(opcional)</span></label>
                            <input id="phone" name="phone" type="text"
                                   class="form-control @error('phone') is-invalid @enderror"
                                   value="{{ old('phone') }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="password" class="form-label">Contraseña</label>
                            <input id="password" name="password" type="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label">Confirmar contraseña</label>
                            <input id="password_confirmation" name="password_confirmation"
                                   type="password" class="form-control" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-dark w-100 mt-4">
                        Crear cuenta
                    </button>
                </form>

                <p class="text-center mt-4 mb-0">
                    ¿Ya tienes una cuenta?
                    <a href="{{ route('login') }}">Inicia sesión</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection