@if (session('success') || session('error') || session('warning') || session('info'))

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

    <div id="appAlert" class="app-alert-overlay">

        <div class="app-alert app-alert-{{ $type }}">

            <div class="app-alert-icon">
                <i class="bi {{ $icons[$type] }}"></i>
            </div>

            <h6 class="app-alert-title">
                {{ $titles[$type] }}
            </h6>

            <p class="app-alert-message">
                {{ $message }}
            </p>

        </div>

    </div>

@endif