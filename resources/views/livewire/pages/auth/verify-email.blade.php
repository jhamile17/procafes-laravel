<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4 p-md-5 text-center">
                <h1 class="h3 mb-3">Verifica tu correo</h1>

                <p class="text-muted mb-3">
                    Enviamos un enlace de verificación a:
                </p>

                <p class="fw-bold mb-4">
                    {{ auth()->user()->email }}
                </p>

                <p class="text-muted mb-4">
                    Revisa tu bandeja de entrada y la carpeta de spam.
                    Debes verificar tu correo antes de acceder a tu perfil,
                    compras y pagos.
                </p>

                @if (session('status'))
                    <div class="alert alert-success text-start" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <button
                    type="button"
                    wire:click="resendVerificationEmail"
                    wire:loading.attr="disabled"
                    class="btn btn-dark w-100"
                >
                    <span wire:loading.remove wire:target="resendVerificationEmail">
                        Reenviar correo de verificación
                    </span>

                    <span wire:loading wire:target="resendVerificationEmail">
                        Enviando...
                    </span>
                </button>

                <p class="text-muted small mt-4 mb-0">
                    El enlace de verificación vence en 60 minutos.
                </p>
            </div>
        </div>
    </div>
</div>