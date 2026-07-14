@props([
    'text' => 'Procesando...'
])

<div class="auth-progress">

    <div class="progress">

        <div
            class="progress-bar progress-bar-striped progress-bar-animated"
            style="width:100%">
        </div>

    </div>

    <small>

        {{ $text }}

    </small>

</div>