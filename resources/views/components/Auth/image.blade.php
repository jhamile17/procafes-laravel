@props([
    'image' => 'login.jpg',
])

<div class="auth-image">

    <img
        src="{{ asset('images/auth/' . $image) }}"
        alt="PROCÁFES"
        class="auth-image-photo">

</div>