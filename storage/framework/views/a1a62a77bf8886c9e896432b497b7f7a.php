<div class="row justify-content-center">
    <div class="col-12 col-md-7 col-lg-5">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4 p-md-5">
                <h1 class="h3 text-center mb-2">¿Olvidaste tu contraseña?</h1>

                <p class="text-muted text-center mb-4">
                    Ingresa tu correo y te enviaremos un enlace para crear una nueva contraseña.
                </p>

               <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('status')): ?>
                <div class="alert alert-success" role="alert">
                    <?php if(session('status') === 'passwords.sent'): ?>
                        Te enviamos un enlace para restablecer tu contraseña.
                    <?php else: ?>
                        <?php echo e(session('status')); ?>

                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <form wire:submit="sendResetLink">
                    <div class="mb-3">
                        <label for="email" class="form-label">Correo electrónico</label>

                        <input
                            id="email"
                            type="email"
                            wire:model="email"
                            class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            required
                            autofocus
                            autocomplete="email"
                        >

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <button type="submit" class="btn btn-dark w-100" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="sendResetLink">
                            Enviar enlace de recuperación
                        </span>

                        <span wire:loading wire:target="sendResetLink">
                            Enviando...
                        </span>
                    </button>
                </form>

                <p class="text-center mt-4 mb-0">
                    <a href="<?php echo e(route('login')); ?>">Volver a iniciar sesión</a>
                </p>
            </div>
        </div>
    </div>
</div><?php /**PATH E:\Pagina-web-\resources\views/livewire/pages/auth/forgot-password.blade.php ENDPATH**/ ?>