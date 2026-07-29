<x-auth.card image="password.jpg">

    {{-- Encabezado --}}
    <div class="text-center mb-4">

        <h1 class="auth-title">
            Recuperar contraseña
        </h1>

        <p class="auth-subtitle">
            Ingresa el correo electrónico asociado a tu cuenta.
            Te enviaremos un enlace para restablecer tu contraseña.
        </p>

    </div>

    {{-- Mensaje de éxito --}}
    @if(session('status'))

        <x-auth.status
            type="success"
            :message="session('status')" />

    @endif

    {{-- Formulario --}}
    <form
        wire:submit="sendResetLink"
        class="auth-form">

        <x-auth.input
            label="Correo electrónico"
            name="email"
            type="email"
            placeholder="correo@ejemplo.com"
            icon="bi-envelope"
            maxlength="255"
            autocomplete="email"
            autocapitalize="off"
            spellcheck="false"
            wire:model.blur="form.email" />

        <button
            type="submit"
            class="auth-submit"
            wire:loading.attr="disabled"
            wire:target="sendResetLink">

            <span
                wire:loading.remove
                wire:target="sendResetLink">

                Enviar enlace de recuperación

            </span>

            <span
                wire:loading
                wire:target="sendResetLink">

                <span class="spinner-border spinner-border-sm me-2"></span>

                Enviando enlace...

            </span>

        </button>

    </form>

    {{-- Volver al login --}}
    <div class="auth-footer">

        <a
            href="{{ route('login') }}"
            wire:navigate>

            ← Volver al inicio de sesión

        </a>

    </div>

</x-auth.card>