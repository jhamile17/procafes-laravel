<x-auth.card>
    {{-- Encabezado --}}
    <div class="auth-header">

        <span class="auth-badge">
            Bienvenido
        </span>

        <h1 class="auth-title">
            Iniciar sesión
        </h1>

        <p class="auth-subtitle">
            Accede a tu cuenta para continuar en PROCÁFES.
        </p>

    </div>

    {{-- Mensaje de sesión --}}
    @if(session('status'))

        <div class="auth-alert auth-alert-success">

            {{ session('status') }}

        </div>

    @endif

    {{-- Google --}}
    <a
        href="{{ route('auth.google.login') }}"
        class="auth-google-btn">

        <i class="bi bi-google"></i>

        <span>

            Continuar con Google

        </span>

    </a>

    {{-- Separador --}}
    <div class="auth-divider">

        <span>

            o continúa con correo

        </span>

    </div>

    {{-- Formulario --}}
    <form
        wire:submit="login"
        class="auth-form">

        <x-auth.input
            label="Correo electrónico"
            name="email"
            type="email"
            placeholder="correo@ejemplo.com"
            icon="bi-envelope"
            wire:model.blur="form.email"
            autocomplete="email"
            autofocus
            :error="$errors->first('form.email')" />

        <x-auth.input
            label="Contraseña"
            name="password"
            type="password"
            placeholder="Ingresa tu contraseña"
            icon="bi-lock"
            wire:model.blur="form.password"
            autocomplete="current-password"
            :error="$errors->first('form.password')" />

        <div class="auth-options">

            <label class="auth-remember">

                <input
                    type="checkbox"
                    wire:model="form.remember">

                <span>

                    Recordarme

                </span>

            </label>

            <a
                href="{{ route('password.request') }}"
                class="auth-forgot">

                ¿Olvidaste tu contraseña?

            </a>

        </div>

        <button
            type="submit"
            class="auth-submit"
            wire:loading.attr="disabled"
            wire:target="login">

            <span wire:loading.remove
            wire:target="login">

                Iniciar sesión

            </span>

            <span wire:loading
            wire:target="login">

                <span class="spinner-border spinner-border-sm me-2"></span>

                Ingresando...

            </span>

        </button>

    </form>

    {{-- Footer --}}
    <div class="auth-footer">

        <span>

            ¿No tienes una cuenta?

        </span>

        <a href="{{ route('register') }}">

            Regístrate

        </a>

    </div>

</x-auth.card>