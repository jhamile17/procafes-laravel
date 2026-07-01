<div class="row justify-content-center">

    <div class="col-md-6">

        <div class="card shadow-sm border-0">

            <div class="card-body p-4">

                <h1 class="h3 text-center mb-3">

                    Recuperar contraseña

                </h1>

                <p class="text-muted text-center mb-4">

                    Ingresa tu correo y te enviaremos un enlace para crear una nueva contraseña.

                </p>

                @if(session('status'))

                    <div class="alert alert-success">

                        {{ session('status') }}

                    </div>

                @endif

                <form wire:submit="sendResetLink">

                    <div class="mb-3">

                        <label class="form-label">

                            Correo electrónico

                        </label>

                        <input
                            type="email"
                            class="form-control @error('form.email') is-invalid @enderror"
                            wire:model.blur="form.email">

                        @error('form.email')

                            <div class="invalid-feedback">

                                {{ $message }}

                            </div>

                        @enderror

                    </div>

                    <button
                        class="btn btn-dark w-100"
                        type="submit"
                        wire:loading.attr="disabled">

                        <span wire:loading.remove>

                            Enviar enlace

                        </span>

                        <span wire:loading>

                            Enviando...

                        </span>

                    </button>

                </form>

                <div class="text-center mt-4">

                    <a href="{{ route('login') }}">

                        Volver al inicio de sesión

                    </a>

                </div>

            </div>

        </div>

    </div>

</div>