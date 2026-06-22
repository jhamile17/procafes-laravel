<div class="row justify-content-center">
    <div class="col-12 col-md-7 col-lg-5">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4 p-md-5">
                <h1 class="h3 text-center mb-2">Iniciar sesión</h1>

                <p class="text-muted text-center mb-4">
                    Accede a tu cuenta para continuar.
                </p>

                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <a href="{{ route('auth.google.login') }}"
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

                <form wire:submit.prevent="login">
                    <div class="mb-3">
                        <label for="email" class="form-label">
                            Correo electrónico
                        </label>

                        <input
                            id="email"
                            type="email"
                            class="form-control @error('state.email') is-invalid @enderror"
                            wire:model.defer="state.email"
                            autocomplete="email"
                            required
                            autofocus
                        >

                        @error('state.email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">
                            Contraseña
                        </label>

                        <div class="input-group">
                            <input
                                id="password"
                                type="password"
                                class="form-control @error('state.password') is-invalid @enderror"
                                wire:model.defer="state.password"
                                autocomplete="current-password"
                                required
                            >

                            <button
                                class="btn btn-outline-secondary"
                                type="button"
                                id="togglePassword"
                                aria-label="Mostrar contraseña"
                                aria-pressed="false"
                            >
                                <i class="bi bi-eye" id="togglePasswordIcon"></i>
                            </button>
                        </div>

                        @error('state.password')
                            <div class="text-danger small mt-1">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input
                                id="remember"
                                type="checkbox"
                                class="form-check-input"
                                wire:model="state.remember"
                            >

                            <label class="form-check-label" for="remember">
                                Recordarme
                            </label>
                        </div>

                        <a href="{{ route('password.request') }}" class="small">
                            ¿Olvidaste tu contraseña?
                        </a>
                    </div>

                    <button
                        type="submit"
                        class="btn btn-dark w-100"
                        wire:loading.attr="disabled"
                        wire:target="login"
                    >
                        <span wire:loading.remove wire:target="login">
                            Iniciar sesión
                        </span>

                        <span wire:loading wire:target="login">
                            <span class="spinner-border spinner-border-sm me-2"
                                  role="status"
                                  aria-hidden="true"></span>
                            Ingresando...
                        </span>
                    </button>
                </form>

                <p class="text-center mt-4 mb-0">
                    ¿Aún no tienes cuenta?
                    <a href="{{ route('register') }}">Regístrate</a>
                </p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const passwordInput = document.getElementById('password');
        const toggleButton = document.getElementById('togglePassword');
        const toggleIcon = document.getElementById('togglePasswordIcon');

        if (!passwordInput || !toggleButton || !toggleIcon) {
            return;
        }

        toggleButton.addEventListener('click', () => {
            const isHidden = passwordInput.type === 'password';

            passwordInput.type = isHidden ? 'text' : 'password';
            toggleIcon.className = isHidden
                ? 'bi bi-eye-slash'
                : 'bi bi-eye';

            toggleButton.setAttribute(
                'aria-label',
                isHidden ? 'Ocultar contraseña' : 'Mostrar contraseña'
            );

            toggleButton.setAttribute(
                'aria-pressed',
                isHidden ? 'true' : 'false'
            );
        });
    });
</script>
@endpush