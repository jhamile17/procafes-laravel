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
              @error('state.email') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
              <label class="form-label">Contraseña</label>
              <input type="password" class="form-control" wire:model.defer="state.password" />
              @error('state.password') <div class="text-danger small">{{ $message }}</div> @enderror
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
            <a href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>
