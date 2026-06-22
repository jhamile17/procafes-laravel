@extends('layouts.auth')

@section('title', 'Verifica tu correo | PROCAFES')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4 p-md-5 text-center">
                <h1 class="h3 mb-3">Verifica tu correo electrónico</h1>

                <p class="text-muted mb-4">
                    Te enviamos un enlace de verificación a tu correo.
                    Confírmalo para activar completamente tu cuenta.
                </p>

                @if (session('status') === 'verification-link-sent')
                    <div class="alert alert-success text-start" role="alert">
                        Se envió un nuevo enlace de verificación a tu correo.
                    </div>
                @endif

                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf

                    <button type="submit" class="btn btn-dark">
                        Reenviar correo de verificación
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}" class="mt-3">
                    @csrf

                    <button type="submit" class="btn btn-link text-decoration-none">
                        Cerrar sesión
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection