<nav class="navbar navbar-expand-lg">

    <div class="container-fluid px-4">

        <x-navbar.brand />

        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarContent"
            aria-controls="navbarContent"
            aria-expanded="false"
            aria-label="Abrir navegación">

            <i class="bi bi-list"></i>

        </button>

        <div
            class="collapse navbar-collapse"
            id="navbarContent">

            <div class="navbar-layout">

                <div class="navbar-navigation">

                    <x-navbar.navigation />

                </div>

                <div class="navbar-search-area">

                    <x-navbar.search />

                </div>

                <div class="navbar-actions-area">

                    <x-navbar.actions />

                </div>

                <div class="navbar-account-area">

                    <x-navbar.account />

                </div>

            </div>

        </div>

    </div>

</nav>