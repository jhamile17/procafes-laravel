<x-auth.card image="login.jpg">

    <div class="text-center mb-4">

        <h1 class="auth-title">

            Restablecer contraseña

        </h1>

        <p class="auth-subtitle">

            Ingresa una nueva contraseña para acceder nuevamente a tu cuenta.

        </p>

    </div>
    
    <form wire:submit="resetPassword">

        <x-auth.input
            label="Correo electrónico"
            name="email"
            type="email"
            icon="bi-envelope"
            wire:model="form.email"
            readonly
            autocomplete="email" />

        <x-auth.input
            label="Nueva contraseña"
            name="password"
            type="password"
            placeholder="Ingrese una nueva contraseña"
            icon="bi-lock"
            wire:model.live.debounce.300ms="form.password"
            autocomplete="new-password"
            maxlength="100" />

        <x-auth.input
            label="Confirmar contraseña"
            name="password_confirmation"
            type="password"
            placeholder="Confirme la contraseña"
            icon="bi-shield-lock"
            wire:model.live.debounce.300ms="form.password_confirmation"
            autocomplete="new-password"
            maxlength="100" />
   
        <button
            type="submit"
            class="auth-submit mt-3"
            wire:loading.attr="disabled">

            <span wire:loading.remove>

                Restablecer contraseña

            </span>

            <span wire:loading>

                Actualizando contraseña...

            </span>

        </button>

    </form>

    <div class="text-center mt-4">

        <a
            href="{{ route('login') }}"
            wire:navigate
            class="auth-link">

            ← Volver al inicio de sesión

        </a>

    </div>

</x-auth.card>