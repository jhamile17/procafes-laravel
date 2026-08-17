<x-auth.card>

    {{-- =========================================================
        ENCABEZADO
    ========================================================== --}}

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


    {{-- =========================================================
        MENSAJE DE SESIÓN
    ========================================================== --}}

    @if(session('status'))

        <div class="auth-alert auth-alert-success">

            {{ session('status') }}

        </div>

    @endif


    {{-- =========================================================
        MENSAJES DE ERROR
    ========================================================== --}}

    @if($errors->has('form.email'))

        <div class="auth-alert auth-alert-error">

            <i class="bi bi-exclamation-circle-fill"></i>

            <div>

                <strong>
                    No se pudo iniciar sesión
                </strong>

                <div>
                    {{ $errors->first('form.email') }}
                </div>

            </div>

        </div>

    @endif


    {{-- =========================================================
        GOOGLE
    ========================================================== --}}

    <a
        href="{{ route('auth.google.login') }}"
        class="auth-google-btn">

        <i class="bi bi-google"></i>

        <span>
            Continuar con Google
        </span>

    </a>


    {{-- =========================================================
        SEPARADOR
    ========================================================== --}}

    <div class="auth-divider">

        <span>
            o continúa con correo
        </span>

    </div>


    {{-- =========================================================
        FORMULARIO
    ========================================================== --}}

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
            :error="$errors->first('form.email')"
        />

        <x-auth.input
            label="Contraseña"
            name="password"
            type="password"
            placeholder="Ingresa tu contraseña"
            icon="bi-lock"
            wire:model.blur="form.password"
            autocomplete="current-password"
            :error="$errors->first('form.password')"
        />


        {{-- =====================================================
            OPCIONES
        ====================================================== --}}

        <div class="auth-options">

            <label class="auth-remember">

                <input
                    type="checkbox"
                    wire:model="form.remember"
                >

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


        {{-- =====================================================
            BOTÓN LOGIN
        ====================================================== --}}

        <button
            type="submit"
            class="auth-submit"
            wire:loading.attr="disabled"
            wire:target="login">

            <span
                wire:loading.remove
                wire:target="login">

                Iniciar sesión

            </span>

            <span
                wire:loading
                wire:target="login">

                <span class="spinner-border spinner-border-sm me-2"></span>

                Ingresando...

            </span>

        </button>

    </form>


    {{-- =========================================================
        FOOTER
    ========================================================== --}}

    <div class="auth-footer">

        <span>
            ¿No tienes una cuenta?
        </span>

        <a href="{{ route('register') }}">
            Regístrate
        </a>

    </div>

</x-auth.card>