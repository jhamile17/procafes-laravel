@props([
    'name',
    'show' => false,
    'maxWidth' => 'lg', // sm | md | lg | xl
])

@php
    $widths = [
        'sm' => 'modal-sm',
        'md' => '',
        'lg' => 'modal-lg',
        'xl' => 'modal-xl',
    ];
@endphp

<div
    x-data="{ open: @js($show) }"
    x-show="open"
    x-on:keydown.escape.window="open = false"
    class="modal fade show d-block"
    style="background: rgba(0,0,0,.5);"
    x-cloak
>

    <div class="modal-dialog {{ $widths[$maxWidth] ?? '' }} modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">

            {{-- HEADER --}}
            @if(isset($header))
                <div class="modal-header border-0">
                    {{ $header }}

                    <button type="button"
                            class="btn-close"
                            x-on:click="open = false">
                    </button>
                </div>
            @endif

            {{-- BODY --}}
            <div class="modal-body">
                {{ $slot }}
            </div>

            {{-- FOOTER --}}
            @if(isset($footer))
                <div class="modal-footer border-0">
                    {{ $footer }}
                </div>
            @endif

        </div>
    </div>
</div>