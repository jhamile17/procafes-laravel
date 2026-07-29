<div class="customer-sidebar">

    <div class="sidebar-profile">

        <form
            action="{{ route('customer.profile.photo') }}"
            method="POST"
            enctype="multipart/form-data"
            id="photoForm">

            @csrf
            @method('PUT')

            <div class="sidebar-avatar-wrapper">

                <img
                    src="{{ auth()->user()->foto_perfil_url }}"
                    alt="{{ auth()->user()->nombre_completo }}"
                    class="sidebar-avatar">

                <button
                    type="button"
                    class="sidebar-avatar-button"
                    onclick="document.getElementById('photoInput').click()">

                    +

                </button>

                <input
                    id="photoInput"
                    type="file"
                    name="foto_perfil"
                    accept="image/*"
                    hidden
                    onchange="document.getElementById('photoForm').submit()">

            </div>

        </form>

        <h5 class="sidebar-name">
            {{ auth()->user()->nombre_completo }}
        </h5>

        <p class="sidebar-email">
            {{ auth()->user()->email }}
        </p>

    </div>

        <nav class="sidebar-menu">

        {{-- Mi Perfil --}}
        <a
            href="{{ route('customer.profile') }}"
            class="sidebar-link {{ request()->routeIs('customer.profile') ? 'active' : '' }}">

            <i class="bi bi-person"></i>

            <span>Mi perfil</span>

        </a>

        {{-- Mis pedidos --}}
        <a
            href="{{ route('customer.orders') }}"
            class="sidebar-link {{ request()->routeIs('customer.orders*') ? 'active' : '' }}">

            <i class="bi bi-bag"></i>

            <span>Mis pedidos</span>

        </a>

        {{-- Favoritos --}}
        <a
            href="{{ route('customer.wishlist') }}"
            class="sidebar-link {{ request()->routeIs('customer.wishlist*') ? 'active' : '' }}">

            <i class="bi bi-heart"></i>

            <span>Favoritos</span>

        </a>
                {{-- Configuración --}}
        <a
            href="{{ route('customer.profile.settings') }}"
            class="sidebar-link {{ request()->routeIs('customer.profile.settings') ? 'active' : '' }}">

            <i class="bi bi-gear"></i>

            <span>Configuración</span>

        </a>

    </nav>

</div>