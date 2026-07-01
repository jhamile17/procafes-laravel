<div class="row justify-content-center">

    <div class="col-md-6">

        <div class="card shadow-sm border-0">

            <div class="card-body p-4">

                <h1 class="h3 text-center mb-3">

                    Nueva contraseña

                </h1>

                <p class="text-muted text-center mb-4">

                    Ingresa una nueva contraseña para tu cuenta.

                </p>

                <form wire:submit="resetPassword">

                    <div class="mb-3">

                        <label class="form-label">

                            Correo electrónico

                        </label>

                        <input
                            type="email"
                            class="form-control @error('form.email') is-invalid @enderror"
                            wire:model="form.email">

                        @error('form.email')

                            <div class="invalid-feedback">

                                {{ $message }}

                            </div>

                        @enderror

                    </div>

                    <div class="mb-3">

                        <label class="form-label">

                            Nueva contraseña

                        </label>

                        <input
                            type="password"
                            class="form-control @error('form.password') is-invalid @enderror"
                            wire:model="form.password">

                        @error('form.password')

                            <div class="invalid-feedback">

                                {{ $message }}

                            </div>

                        @enderror

                    </div>

                    <div class="mb-4">

                        <label class="form-label">

                            Confirmar contraseña

                        </label>

                        <input
                            type="password"
                            class="form-control"
                            wire:model="form.password_confirmation">

                    </div>

                    <button
                        class="btn btn-dark w-100"
                        type="submit"
                        wire:loading.attr="disabled">

                        <span wire:loading.remove>

                            Cambiar contraseña

                        </span>

                        <span wire:loading>

                            Actualizando...

                        </span>

                    </button>

                </form>

            </div>

        </div>

    </div>

</div>