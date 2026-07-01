<div class="row justify-content-center">

    <div class="col-12 col-md-7 col-lg-5">

        <div class="card shadow-sm border-0">

            <div class="card-body p-4 p-md-5">

                <h1 class="h3 text-center mb-2">
                    Iniciar sesión
                </h1>

                <p class="text-muted text-center mb-4">
                    Accede a tu cuenta para continuar.
                </p>

                @if(session('status'))

                    <div class="alert alert-success">

                        {{ session('status') }}

                    </div>

                @endif

                <a
                    href="{{ route('auth.google.login') }}"
                    class="btn btn-outline-dark w-100 mb-3">

                    <i class="bi bi-google me-2"></i>

                    Continuar con Google

                </a>

                <div class="position-relative text-center my-4">

                    <hr>

                    <span class="position-absolute top-50 start-50 translate-middle bg-white px-2 text-muted small">

                        o continúa con correo

                    </span>

                </div>

                <form wire:submit="login">

                    {{-- Correo --}}
                    <div class="mb-3">

                        <label class="form-label">

                            Correo electrónico

                        </label>

                        <input
                            type="email"
                            class="form-control @error('form.email') is-invalid @enderror"
                            wire:model.blur="form.email"
                            autocomplete="email"
                            autofocus>

                        @error('form.email')

                            <div class="invalid-feedback">

                                {{ $message }}

                            </div>

                        @enderror

                    </div>

                    {{-- Contraseña --}}
                    <div class="mb-3">

                        <label class="form-label">

                            Contraseña

                        </label>

                        <div class="input-group">

                            <input
                                id="password"
                                type="password"
                                class="form-control @error('form.password') is-invalid @enderror"
                                wire:model="form.password"
                                autocomplete="current-password">

                            <button
                                class="btn btn-outline-secondary"
                                type="button"
                                id="togglePassword">

                                <i
                                    class="bi bi-eye"
                                    id="togglePasswordIcon">
                                </i>

                            </button>

                        </div>

                        @error('form.password')

                            <div class="text-danger small mt-1">

                                {{ $message }}

                            </div>

                        @enderror

                    </div>

                    {{-- Recordarme --}}
                    <div class="d-flex justify-content-between align-items-center mb-4">

                        <div class="form-check">

                            <input
                                class="form-check-input"
                                type="checkbox"
                                wire:model="form.remember"
                                id="remember">

                            <label
                                class="form-check-label"
                                for="remember">

                                Recordarme

                            </label>

                        </div>

                        <a
                            href="{{ route('password.request') }}"
                            class="small">

                            ¿Olvidaste tu contraseña?

                        </a>

                    </div>

                    <button
                        type="submit"
                        class="btn btn-dark w-100"
                        wire:loading.attr="disabled">

                        <span wire:loading.remove>

                            Iniciar sesión

                        </span>

                        <span wire:loading>

                            <span
                                class="spinner-border spinner-border-sm me-2">
                            </span>

                            Ingresando...

                        </span>

                    </button>

                </form>

                <p class="text-center mt-4 mb-0">

                    ¿No tienes una cuenta?

                    <a href="{{ route('register') }}">

                        Regístrate

                    </a>

                </p>

            </div>

        </div>

    </div>

</div>

@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', () => {

    const password = document.getElementById('password');

    const button = document.getElementById('togglePassword');

    const icon = document.getElementById('togglePasswordIcon');

    if (!password || !button || !icon) {

        return;

    }

    button.addEventListener('click', () => {

        const visible = password.type === 'password';

        password.type = visible
            ? 'text'
            : 'password';

        icon.className = visible
            ? 'bi bi-eye-slash'
            : 'bi bi-eye';

    });

});

</script>

@endpush