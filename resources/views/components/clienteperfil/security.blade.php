@php
    $user = auth()->user();
@endphp
<div class="customer-card mt-4">

    <div class="customer-card-header">

        <div>

            <span class="customer-card-badge">
                Seguridad
            </span>

            <h2 class="customer-card-title">
                Seguridad de la cuenta
            </h2>

            @if($user->has_local_password)

                <p class="customer-card-subtitle">
                    Cambia periódicamente tu contraseña para mantener tu cuenta protegida.
                </p>

            @else

                <p class="customer-card-subtitle">
                    Tu cuenta fue creada mediante Google. Crea una contraseña para que también puedas iniciar sesión con tu correo electrónico y contraseña.
                </p>

            @endif

        </div>
    </div>
    <div class="customer-card-body">
        <form
            action="{{ route('customer.profile.password.update') }}"
            method="POST"
            novalidate>

            @csrf
            @method('PUT')

            <div class="row g-4">

                @if($user->has_local_password)

                    {{-- Contraseña actual --}}
                    <div class="col-md-4">

                        <label
                            for="current_password"
                            class="customer-label">

                            Contraseña actual

                        </label>

                        <div class="customer-password">

                            <input
                                type="password"
                                id="current_password"
                                name="current_password"
                                autocomplete="current-password"
                                class="form-control @error('current_password') is-invalid @enderror">

                            <button
                                type="button"
                                class="customer-password-toggle auth-password-toggle"
                                data-password="current_password"
                                aria-label="Mostrar contraseña">

                                <i class="bi bi-eye"></i>

                            </button>

                        </div>

                        @error('current_password')

                            <div class="invalid-feedback d-block">

                                {{ $message }}

                            </div>

                        @enderror

                    </div>

                @endif

                {{-- Nueva contraseña --}}
                <div class="{{ $user->has_local_password ? 'col-md-4' : 'col-md-6' }}">

                    <label
                        for="password"
                        class="customer-label">

                        {{ $user->has_local_password ? 'Nueva contraseña' : 'Crear contraseña' }}

                    </label>

                    <div class="customer-password">

                        <input
                            type="password"
                            id="password"
                            name="password"
                            autocomplete="new-password"
                            class="form-control @error('password') is-invalid @enderror">

                        <button
                            type="button"
                            class="customer-password-toggle auth-password-toggle"
                            data-password="password"
                            aria-label="Mostrar contraseña">

                                <i class="bi bi-eye"></i>

                        </button>

                    </div>

                    @error('password')

                        <div class="invalid-feedback d-block">

                            {{ $message }}

                        </div>

                    @enderror

                </div>

                {{-- Confirmar contraseña --}}
                <div class="{{ $user->has_local_password ? 'col-md-4' : 'col-md-6' }}">

                    <label
                        for="password_confirmation"
                        class="customer-label">

                        Confirmar contraseña

                    </label>

                    <div class="customer-password">

                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            autocomplete="new-password"
                            class="form-control @error('password_confirmation') is-invalid @enderror">

                        <button
                            type="button"
                            class="customer-password-toggle auth-password-toggle"
                            data-password="password_confirmation"
                            aria-label="Mostrar contraseña">

                            <i class="bi bi-eye"></i>

                        </button>

                    </div>

                    @error('password_confirmation')

                        <div class="invalid-feedback d-block">

                            {{ $message }}

                        </div>

                    @enderror

                </div>

            </div>

            <div class="d-flex justify-content-end mt-5">

                <button
                    type="submit"
                    class="customer-button">

                    <i class="bi bi-shield-lock me-2"></i>

                    {{ $user->has_local_password ? 'Cambiar contraseña' : 'Crear contraseña' }}

                </button>

            </div>

        </form>

    </div>

</div>