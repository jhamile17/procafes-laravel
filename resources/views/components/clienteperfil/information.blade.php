<div class="customer-card">

    <div class="customer-card-header">

        <div>

            <span class="customer-card-badge">
                Información personal
            </span>

            <h2 class="customer-card-title">
                Datos de la cuenta
            </h2>

            <p class="customer-card-subtitle">
                Mantén tu información actualizada para agilizar tus compras y entregas.
            </p>

        </div>

        <a
            href="{{ route('customer.profile.edit') }}"
            class="customer-btn btn-sm px-3 py-2">

            <i class="bi bi-pencil-square"></i>

            Editar información

        </a>

    </div>

    <div class="customer-card-body">

        <div class="row g-4">

            <div class="col-md-6">

                <label class="customer-label">
                    Nombre completo
                </label>

                <div class="customer-value">
                    {{ auth()->user()->nombre_completo }}
                </div>

            </div>

            <div class="col-md-6">

                <label class="customer-label">
                    Correo electrónico
                </label>

                <div class="customer-value">
                    {{ auth()->user()->email }}
                </div>

            </div>

            <div class="col-md-6">

                <label class="customer-label">
                    Documento
                </label>

                <div class="customer-value">
                    {{ auth()->user()->numero_documento ?: 'No registrado' }}
                </div>

            </div>

            <div class="col-md-6">

                <label class="customer-label">
                    Celular
                </label>

                <div class="customer-value">
                    {{ auth()->user()->celular ?: 'No registrado' }}
                </div>

            </div>

        </div>

    </div>

</div>