@props([
    'image' => 'login.jpg',
])

<div class="auth-wrapper">

    <div {{ $attributes->merge(['class' => 'auth-card']) }}>

        <aside class="auth-card-image">

            <x-auth.image
                :image="$image" />

        </aside>

        <section class="auth-card-content">

            {{ $slot }}

        </section>

    </div>

</div>