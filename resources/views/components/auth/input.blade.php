@props([
    'label' => null,
    'name',
    'type' => 'text',
    'placeholder' => null,
    'icon' => null,
    'error' => null,
])

@php
    $wireModel = $attributes->wire('model');
    $error ??= $wireModel ? $errors->first($wireModel->value()) : null;
@endphp

<div class="auth-form-group">

    @if($label)

        <label
            for="{{ $name }}"
            class="auth-label">

            {{ $label }}

        </label>

    @endif

    <div class="auth-input-wrapper">

        @if($icon)

            <span class="auth-input-icon">

                <i class="bi {{ $icon }}"></i>

            </span>

        @endif

        <input
            id="{{ $name }}"
            name="{{ $name }}"
            type="{{ $type }}"
            placeholder="{{ $placeholder }}"
            autocomplete="{{ $attributes->get('autocomplete') }}"
            aria-invalid="{{ filled($error) ? 'true' : 'false' }}"
            aria-describedby="{{ filled($error) ? $name.'-error' : null }}"
            {{ $attributes->except('autocomplete')->class([
                'auth-input',
                'auth-input-with-icon' => filled($icon),
                'auth-input-password' => $type === 'password',
                'is-invalid' => filled($error),
            ]) }}>

        @if($type === 'password')

            <button
                type="button"
                class="auth-password-toggle"
                data-password="{{ $name }}">

                <i class="bi bi-eye"></i>

            </button>

        @endif

    </div>

    @if(filled($error))

        <small
            id="{{ $name }}-error"
            class="auth-error">

            {{ $error }}

        </small>

    @endif

</div>