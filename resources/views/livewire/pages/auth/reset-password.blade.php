<div class="row justify-content-center">
    <div class="col-12 col-md-7 col-lg-5">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4 p-md-5">
                <h1 class="h3 text-center mb-2">Crear nueva contraseña</h1>

                <p class="text-muted text-center mb-4">
                    Elige una contraseña segura para tu cuenta.
                </p>

                <form wire:submit="resetPassword">
                    <div class="mb-3">
                        <label for="email" class="form-label">Correo electrónico</label>

                        <input
                            id="email"
                            type="email"
                            wire:model="email"
                            class="form-control @error('email') is-invalid @enderror"
                            required
                            autocomplete="email"
                        >

                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Nueva contraseña</label>

                        <input
                            id="password"
                            type="password"
                            wire:model="password"
                            class="form-control @error('password') is-invalid @enderror"
                            required
                            autocomplete="new-password"
                        >

                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label">
                            Confirmar nueva contraseña
                        </label>

                        <input
                            id="password_confirmation"
                            type="password"
                            wire:model="password_confirmation"
                            class="form-control"
                            required
                            autocomplete="new-password"
                        >
                    </div>

                    <button
                        type="submit"
                        class="btn btn-dark w-100"
                        wire:loading.attr="disabled"
                    >
                        <span wire:loading.remove wire:target="resetPassword">
                            Guardar nueva contraseña
                        </span>

                        <span wire:loading wire:target="resetPassword">
                            Guardando...
                        </span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>