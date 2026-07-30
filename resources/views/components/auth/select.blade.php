@props([
    'label' => null,
    'name',
    'error' => null,
])

<div class="auth-form-group">

    @if($label)

        <label
            for="{{ $name }}"
            class="auth-label">

            {{ $label }}

        </label>

    @endif

    <div class="auth-input-wrapper">

        <select
            id="{{ $name }}"
            name="{{ $name }}"
            aria-invalid="{{ filled($error) ? 'true' : 'false' }}"
            aria-describedby="{{ filled($error) ? $name.'-error' : null }}"
            {{ $attributes->class([
                'auth-select',
                'is-invalid' => filled($error),
            ]) }}>

            {{ $slot }}

        </select>

    </div>

    @if(filled($error))

        <small
            id="{{ $name }}-error"
            class="auth-error">

            {{ $error }}

        </small>

    @endif

</div>