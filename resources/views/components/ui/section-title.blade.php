@props([
    'tag' => null,
    'title',
    'description' => null,
])

<div class="section-header">

    @if($tag)
        <span class="section-tag">
            {{ $tag }}
        </span>
    @endif

    <h2 class="section-title">
        {{ $title }}
    </h2>

    @if($description)
        <p class="section-description">
            {{ $description }}
        </p>
    @endif

</div>