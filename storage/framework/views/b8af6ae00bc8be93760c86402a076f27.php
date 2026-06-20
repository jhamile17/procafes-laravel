<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-12 col-md-6">
      <div class="card shadow-sm">
        <div class="card-body">
          <h4 class="mb-3 text-center">Iniciar sesión</h4>

          <form wire:submit.prevent="login">

            <div class="mb-3">
              <label class="form-label">Email</label>
              <input type="email" class="form-control" wire:model.defer="state.email" />
              <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['state.email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger small"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <div class="mb-3">
              <label class="form-label">Contraseña</label>
              <input type="password" class="form-control" wire:model.defer="state.password" />
              <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['state.password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger small"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <div class="mb-3 form-check">
              <input type="checkbox" class="form-check-input" id="remember" wire:model="state.remember">
              <label class="form-check-label" for="remember">Recordarme</label>
            </div>

            <div class="d-grid">
              <button class="btn btn-primary" type="submit" wire:loading.attr="disabled">
                Entrar
              </button>
            </div>

          </form>

          <div class="mt-3 text-center">
            <a href="<?php echo e(route('password.request')); ?>">¿Olvidaste tu contraseña?</a>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>
<?php /**PATH E:\Pagina-web-\resources\views/livewire/pages/auth/login.blade.php ENDPATH**/ ?>