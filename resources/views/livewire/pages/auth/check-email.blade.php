<x-auth.card image="login.jpg">
    <div class="text-center">
        <h2 class="auth-title mb-3">
            Revisa tu correo electrónico
        </h2>
        <p class="auth-description">
            Hemos enviado un enlace de verificación a la siguiente dirección:
        </p>
    </div>
    <div class="alert alert-light border text-center my-4">
        <i class="bi bi-envelope-fill"></i>
        <strong>{{ $email }}</strong>
    </div>
    <div class="mb-4">

        <div class="d-flex align-items-center mb-2">
            <i class="bi bi-check-circle-fill text-success me-2"></i>
            <span>Abre tu bandeja de entrada.</span>
        </div>

        <div class="d-flex align-items-center mb-2">
            <i class="bi bi-check-circle-fill text-success me-2"></i>
            <span>Haz clic en el enlace de verificación.</span>
        </div>

        <div class="d-flex align-items-center">
            <i class="bi bi-check-circle-fill text-success me-2"></i>
            <span>Tu cuenta quedará activada automáticamente.</span>
        </div>

    </div>

    @if(session('success'))
        <div class="alert alert-success rounded-3">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('register.resend') }}" method="POST">

        @csrf

        <input
            type="hidden"
            name="email"
            value="{{ $email }}">

        <button
            id="btnResend"
            type="submit"
            class="btn btn-outline-primary w-100"
            data-seconds="{{ $seconds }}"
            disabled>
            <i class="bi bi-arrow-clockwise me-2"></i>
            Reenviar correo
            (<span id="countdown">{{ $seconds }}</span>)
        </button>
    </form>
    <a
        href="{{ route('home') }}"
        class="btn btn-primary w-100 mt-3">
        <i class="bi bi-house-door-fill me-2"></i>
        Volver al inicio
    </a>
    <div class="text-center mt-4">
        <small class="text-muted">
            ¿No encuentras el correo?
            <br>
            Revisa también las carpetas de
            <strong>Spam</strong> o
            <strong>Correo no deseado</strong>.

        </small>

    </div>

</x-auth.card>

