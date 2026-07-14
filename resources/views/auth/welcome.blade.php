@extends('layouts.auth')

@section('content')
<div
    class="auth-success fade-up"
    data-redirect="{{ route('products') }}">
<div class="auth-success">

    <x-auth.logo />

    <div class="auth-success-divider"></div>

    <p class="auth-success-tag">
        Ya eres parte de
    </p>

    <h1 class="auth-success-brand">
        PROCÁFES
    </h1>

    <p class="auth-success-name">
        {{ ucwords(strtolower(auth()->user()->nombres)) }}
    </p>

    <p class="auth-success-description">
        Tu cuenta está lista.
        Comienza a disfrutar la experiencia PROCÁFES.
    </p>

    <div class="auth-success-message">

        <i class="bi bi-cup-hot-fill"></i>

        <span>
            Descubre nuestros cafés, frappés y productos favoritos.
        </span>

    </div>

    <a
        href="{{ route('products') }}"
        class="btn btn-primary auth-action">
        Comenzar mi experiencia
    </a>

    <div class="auth-progress">
        <div class="progress">
            <div class="progress-bar progress-bar-striped progress-bar-animated w-100"></div>
        </div>

        <small class="mt-3 d-block">
            Ingresando a la tienda...
        </small>
    </div>
</div>
@endsection