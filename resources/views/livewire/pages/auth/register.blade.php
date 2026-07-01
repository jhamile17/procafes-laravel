<div class="row justify-content-center">
    <div class="col-12 col-lg-8 col-xl-7">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4 p-md-5">
                <h1 class="h3 text-center mb-2">
                    Crea tu cuenta
                </h1>
                <p class="text-muted text-center mb-4">
                    Regístrate para comprar y seguir tus pedidos.
                </p>
                <a
                    href="{{ route('auth.google.register') }}"
                    class="btn btn-outline-dark w-100 mb-3">
                    Continuar con Google
                </a>
                <div class="text-center text-muted small mb-4">
                    o regístrate con tu correo
                </div>
                <form wire:submit="register">
                    <div class="row g-3">
                        {{-- Tipo Documento --}}
                        <div class="col-md-4">
                            <label class="form-label">
                                Tipo de documento
                            </label>
                            <select
                                class="form-select"
                                wire:model.live="form.tipo_documento">

                                <option value="DNI">DNI</option>

                                <option value="CE">
                                    Carné de Extranjería
                                </option>

                                <option value="PASAPORTE">
                                    Pasaporte
                                </option>
                            </select>
                        </div>
                        {{-- Número Documento --}}
                        <div class="col-md-8">
                            <label class="form-label">
                                Número de documento
                            </label>
                            <input
                                type="text"
                                class="form-control @error('form.numero_documento') is-invalid @enderror"
                                wire:model.live="form.numero_documento"
                                wire:blur="form.buscarDocumento">
                            @error('form.numero_documento')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                            @if($form->estadoDocumento === \App\Livewire\Forms\RegisterForm::DOCUMENTO_CONSULTANDO)
                                <small class="text-primary">
                                    Consultando documento...
                                </small>
                            @endif
                            @if($form->estadoDocumento === \App\Livewire\Forms\RegisterForm::DOCUMENTO_ENCONTRADO)
                                <small class="text-success">
                                    Datos encontrados correctamente.
                                </small>
                            @endif
                            @if($form->estadoDocumento === \App\Livewire\Forms\RegisterForm::DOCUMENTO_NO_ENCONTRADO)
                                <small class="text-warning">
                                    No encontramos el documento. Puedes completar los datos manualmente.
                                </small>
                            @endif
                        </div>
                        {{-- Nombres --}}
                        <div class="col-md-4">
                            <label class="form-label">
                                Nombres
                            </label>
                            <input
                                type="text"
                                class="form-control @error('form.nombres') is-invalid @enderror"
                                wire:model.live="form.nombres"
                                @disabled(!$form->permitirEdicionManual)>
                            @error('form.nombres')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        {{-- Apellido paterno --}}
                        <div class="col-md-4">
                            <label class="form-label">
                                Apellido paterno
                            </label>
                            <input
                                type="text"
                                class="form-control @error('form.apellido_paterno') is-invalid @enderror"
                                wire:model.live="form.apellido_paterno"
                                @disabled(!$form->permitirEdicionManual)>

                            @error('form.apellido_paterno')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        {{-- Apellido materno --}}
                        <div class="col-md-4">

                            <label class="form-label">

                                Apellido materno

                            </label>

                            <input
                                type="text"
                                class="form-control @error('form.apellido_materno') is-invalid @enderror"
                                wire:model.live="form.apellido_materno"
                                @disabled(!$form->permitirEdicionManual)>

                            @error('form.apellido_materno')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        {{-- Correo --}}
                        <div class="col-md-6">
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
                        {{-- Celular --}}
                        <div class="col-md-6">
                            <label class="form-label">
                                Celular
                            </label>
                            <input
                                type="text"
                                class="form-control @error('form.celular') is-invalid @enderror"
                                wire:model.live="form.celular">
                            @error('form.celular')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        {{-- Contraseña --}}
                        <div class="col-md-6">
                            <label class="form-label">
                                Contraseña
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
                        {{-- Confirmar contraseña --}}
                        <div class="col-md-6">
                            <label class="form-label">
                                Confirmar contraseña
                            </label>
                            <input
                                type="password"
                                class="form-control"
                                wire:model="form.password_confirmation">
                        </div>
                    </div>
                    <button
                        type="submit"
                        class="btn btn-dark w-100 mt-4"
                        wire:loading.attr="disabled">
                        <span wire:loading.remove>
                            Crear cuenta
                        </span>
                        <span wire:loading>
                            Registrando...
                        </span>
                    </button>
                </form>
                <p class="text-center mt-4 mb-0">
                    ¿Ya tienes una cuenta?
                    <a href="{{ route('login') }}">
                        Inicia sesión
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>