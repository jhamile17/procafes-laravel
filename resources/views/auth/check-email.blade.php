@extends('layouts.auth')
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="card shadow border-0 rounded-4">
                <div class="card-body p-5 text-center">
                    <img
                        src="{{ asset('images/logo.jpg') }}"
                        alt="PROCÁFES"
                        style="height: 90px"
                        class="mb-4">
                    <h2 class="fw-bold mb-3">
                        Revisa tu correo
                    </h2>
                    <p class="text-muted">
                        Hemos enviado un enlace de activación a:
                    </p>
                    <div class="alert alert-light border my-4">
                        <strong>{{ $email }}</strong>
                    </div>
                    <p class="text-muted">
                        Haz clic en el enlace del correo para activar tu cuenta.
                    </p>
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    <form
                        action="{{ route('register.resend') }}"
                        method="POST">
                        @csrf
                        <input
                            type="hidden"
                            name="email"
                            value="{{ $email }}">
                        <button
                            id="btnResend"
                            type="submit"
                            class="btn btn-outline-dark w-100"
                            data-seconds="{{ $seconds }}"
                            disabled>
                            Reenviar correo
                            (<span id="countdown">{{ $seconds }}</span>)
                        </button>
                    </form>
                    <div class="mt-4">
                        <small class="text-muted">
                            ¿No encuentras el correo?
                            <br>
                            Revisa también la carpeta de Spam o Promociones.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
