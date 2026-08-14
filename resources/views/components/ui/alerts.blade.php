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

<div
    id="appAlert"
    style="
        position:fixed;
        top:20px;
        right:20px;
        background:#dc3545;
        color:white;
        padding:20px;
        border-radius:10px;
        z-index:999999;
    "
>
    ALERTA FUNCIONANDO
</div>

@endif