{{--==========================================================================
    NAVBAR SEARCH
    --------------------------------------------------------------------------
    Buscador principal de productos.
==========================================================================--}}

<form
    class="navbar-search"
    action="{{ route('products') }}"
    method="GET"
    role="search">

    <div class="input-group">

        <input
            id="navbar-search"
            type="search"
            name="search"
            class="form-control"
            value="{{ request('search') }}"
            placeholder="Buscar productos..."
            aria-label="Buscar productos"
            autocomplete="off"
            spellcheck="false">

        <button
            type="submit"
            class="btn btn-primary navbar-search-btn"
            aria-label="Buscar productos">

            <i
                class="bi bi-search"
                aria-hidden="true">
            </i>

        </button>

    </div>

</form>