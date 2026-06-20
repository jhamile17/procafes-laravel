<a href="{{ route('cart.index') }}"
   class="btn btn-outline-dark position-relative rounded-pill">

    <i class="bi bi-cart3"></i>

    @if(session('cart_count'))
        <span class="position-absolute top-0 start-100 translate-middle badge bg-danger">
            {{ session('cart_count') }}
        </span>
    @endif

</a>