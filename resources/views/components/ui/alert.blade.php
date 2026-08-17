@if(session('success') || session('error') || session('warning') || session('info'))

    @php

        $type = session('success')
            ? 'success'
            : (session('error')
                ? 'error'
                : (session('warning')
                    ? 'warning'
                    : 'info'));

        $message = session($type);

        $titles = [
            'success' => 'Éxito',
            'error' => 'Error',
            'warning' => 'Advertencia',
            'info' => 'Información',
        ];

        $icons = [
            'success' => 'bi-check-lg',
            'error' => 'bi-x-lg',
            'warning' => 'bi-exclamation-lg',
            'info' => 'bi-info-lg',
        ];

    @endphp

    <div
        id="appAlert"
        class="app-alert-overlay"
    >

        <div class="app-alert app-alert-{{ $type }}">

            <div class="app-alert-icon">

                <i class="bi {{ $icons[$type] }}"></i>

            </div>

            <div class="app-alert-content">

                <div class="app-alert-title">

                    {{ $titles[$type] }}

                </div>

                <div class="app-alert-message">

                    {{ $message }}

                </div>

            </div>

            <button
                type="button"
                class="app-alert-close"
                aria-label="Cerrar"
            >

                <i class="bi bi-x"></i>

            </button>

        </div>

    </div>

@endif