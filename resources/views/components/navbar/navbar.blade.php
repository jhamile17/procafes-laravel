<nav class="navbar navbar-expand-xl">

    <div class="container">

        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarContent"
            aria-controls="navbarContent"
            aria-expanded="false"
            aria-label="Toggle navigation">

            <span class="navbar-toggler-icon"></span>

        </button>

        <div class="collapse navbar-collapse" id="navbarContent">

            <div class="navbar-content w-100">

                <div class="navbar-left">

                    <x-navbar.brand />

                    <x-navbar.navigation />

                </div>

                <div class="navbar-search-wrapper">

                    <x-navbar.search />

                </div>

                <div class="navbar-right">

                    <x-navbar.actions />

                    <x-navbar.account />

                </div>

            </div>

        </div>

    </div>

</nav>