<div class="row justify-content-center">

    <div class="col-md-6">

        <div class="card shadow-sm border-0">

            <div class="card-body text-center p-5">

                <h1 class="h3 mb-3">

                    Verifica tu correo

                </h1>

                <p class="text-muted mb-4">

                    Hemos enviado un enlace de verificación a tu correo electrónico.

                    Revisa también la carpeta de Spam o Correo no deseado.

                </p>

                @if(session('status'))

                    <div class="alert alert-success">

                        {{ session('status') }}

                    </div>

                @endif

                <button
                    class="btn btn-dark"
                    wire:click="resendVerificationEmail"
                    wire:loading.attr="disabled">

                    <span wire:loading.remove>

                        Reenviar correo

                    </span>

                    <span wire:loading>

                        Enviando...

                    </span>

                </button>

            </div>

        </div>

    </div>

</div>