<x-auth.card image="register.jpg">
    <header class="auth-header">
        <h1 class="auth-title">
            Crear cuenta
        </h1>
        <p class="auth-subtitle">
            Crea tu cuenta para comprar, seguir tus pedidos y disfrutar
            de la experiencia PROCÁFES.
        </p>
    </header>
    <a
        href="{{ route('auth.google.register') }}"
        class="auth-google-btn">
        <i class="bi bi-google"></i>
        <span>
            Continuar con Google
        </span>
    </a>
    <div class="auth-divider">
        <span>
            o regístrate con tu correo
        </span>
    </div>
    <form
        wire:submit="register"
        class="auth-form">
    <div class="auth-grid">

    <x-auth.select
        label="Tipo de documento"
        name="tipo_documento"
        wire:model.live="form.tipo_documento">

        <option value="DNI">DNI</option>

        <option value="RUC">RUC</option>

        <option value="CE">
            Carné de Extranjería
        </option>

        <option value="PASAPORTE">
            Pasaporte
        </option>

    </x-auth.select>

    <x-auth.input
        label="Número de documento"
        name="numero_documento"
        placeholder="Ingrese su documento"

        wire:model.live.debounce.500ms="form.numero_documento"

        maxlength="{{ match($form->tipo_documento){

            'DNI' => 8,

            'RUC' => 11,

            default => 20,

        } }}"

        inputmode="{{ in_array($form->tipo_documento,['DNI','RUC']) ? 'numeric' : 'text' }}"

        oninput="if(['DNI','RUC'].includes('{{ $form->tipo_documento }}')) this.value=this.value.replace(/[^0-9]/g,'');"/>

</div>
@if($form->estadoDocumento === \App\Livewire\Forms\RegisterForm::DOCUMENTO_CONSULTANDO)

    <x-auth.status
        type="info"
        message="Consultando documento..." />

@endif

@if($form->estadoDocumento === \App\Livewire\Forms\RegisterForm::DOCUMENTO_ENCONTRADO)

    <x-auth.status
        type="success"
        message="Documento encontrado correctamente." />

@endif

@if($form->estadoDocumento === \App\Livewire\Forms\RegisterForm::DOCUMENTO_NO_ENCONTRADO)

    <x-auth.status
        type="warning"
        message="No encontramos el documento. Puedes completar los datos manualmente." />

@endif
<div class="auth-grid">

    <x-auth.input
        label="Nombres"
        name="nombres"
        placeholder="Ingrese sus nombres"
        oninput="this.value=this.value.replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñÜü\s]/g,'')
        .replace(/\s{2,}/g,' ')
        .slice(0,100);;"
        wire:model.blur="form.nombres"
        :disabled="!$form->permitirEdicionManual"
        />

    <x-auth.input
        label="Apellido paterno"
        name="apellido_paterno"
        placeholder="Apellido paterno"
        oninput="this.value=this.value.replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñÜü\s]/g,'')
        .replace(/\s{2,}/g,' ')
        .slice(0,100);;"
        wire:model.blur="form.apellido_paterno"
        :disabled="!$form->permitirEdicionManual"
        />

</div>
    <x-auth.input
        label="Apellido materno"
        name="apellido_materno"
        placeholder="Apellido materno"
        oninput="this.value=this.value.replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñÜü\s]/g,'')
        .replace(/\s{2,}/g,' ')
        .slice(0,100);;"
        wire:model.blur="form.apellido_materno"
        :disabled="!$form->permitirEdicionManual"
        />
    <div class="auth-grid">

    <x-auth.input
        label="Correo electrónico"
        name="email"
        type="email"
        placeholder="correo@ejemplo.com"
        icon="bi-envelope"
        maxlength="255"
        spellcheck="false"
        autocapitalize="off"
        autocomplete="email"
        wire:model.blur="form.email"
        />

    <x-auth.input
        label="Celular"
        name="celular"
        placeholder="987654321"
        icon="bi-phone"
        autocomplete="tel"
        wire:model.blur="form.celular"
        maxlength="9"
        inputmode="numeric"
        oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,9);"
    />
</div>
<div class="auth-grid">

    <x-auth.input
        label="Contraseña"
        name="password"
        type="password"
        icon="bi-lock"
        autocomplete="new-password"
        maxlength="100"
        wire:model.defer="form.password"
        />

    <x-auth.input
        label="Confirmar contraseña"
        name="password_confirmation"
        type="password"
        icon="bi-lock-fill"
        autocomplete="new-password"
        maxlength="100"
        wire:model.defer="form.password_confirmation"
        />

</div>
<button
    type="submit"
    class="auth-submit"
    wire:loading.attr="disabled"
    wire:target="register">

    <span
        wire:loading.remove
        wire:target="register">

        Crear cuenta

    </span>

    <span
        wire:loading
        wire:target="register">

        Creando cuenta...

    </span>

</button>
<p class="auth-footer">

    ¿Ya tienes una cuenta?

    <a href="{{ route('login') }}">

        Inicia sesión

    </a>

</p>

</form>

</x-auth.card>