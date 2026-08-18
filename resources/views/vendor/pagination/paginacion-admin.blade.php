@if ($paginator->hasPages())

    <nav
        class="admin-pagination"
        aria-label="Paginación administrativa"
    >

        <ul class="admin-pagination-list">

            {{-- Anterior --}}
            @if ($paginator->onFirstPage())

                <li class="admin-pagination-item disabled">

                    <span class="admin-pagination-link">

                        <i class="bi bi-chevron-left"></i>

                        <span class="d-none d-md-inline ms-1">
                            Anterior
                        </span>

                    </span>

                </li>

            @else

                <li class="admin-pagination-item">

                    <a
                        href="{{ $paginator->previousPageUrl() }}"
                        class="admin-pagination-link"
                        rel="prev"
                        aria-label="Página anterior"
                    >

                        <i class="bi bi-chevron-left"></i>

                        <span class="d-none d-md-inline ms-1">
                            Anterior
                        </span>

                    </a>

                </li>

            @endif


            {{-- Números --}}
            @foreach ($elements as $element)

                @if (is_string($element))

                    <li class="admin-pagination-item disabled">

                        <span class="admin-pagination-link">
                            {{ $element }}
                        </span>

                    </li>

                @endif


                @if (is_array($element))

                    @foreach ($element as $page => $url)

                        @if ($page == $paginator->currentPage())

                            <li class="admin-pagination-item active">

                                <span class="admin-pagination-link">
                                    {{ $page }}
                                </span>

                            </li>

                        @else

                            <li class="admin-pagination-item">

                                <a
                                    href="{{ $url }}"
                                    class="admin-pagination-link"
                                >
                                    {{ $page }}
                                </a>

                            </li>

                        @endif

                    @endforeach

                @endif

            @endforeach


            {{-- Siguiente --}}
            @if ($paginator->hasMorePages())

                <li class="admin-pagination-item">

                    <a
                        href="{{ $paginator->nextPageUrl() }}"
                        class="admin-pagination-link"
                        rel="next"
                        aria-label="Página siguiente"
                    >

                        <span class="d-none d-md-inline me-1">
                            Siguiente
                        </span>

                        <i class="bi bi-chevron-right"></i>

                    </a>

                </li>

            @else

                <li class="admin-pagination-item disabled">

                    <span class="admin-pagination-link">

                        <span class="d-none d-md-inline me-1">
                            Siguiente
                        </span>

                        <i class="bi bi-chevron-right"></i>

                    </span>

                </li>

            @endif

        </ul>

    </nav>

@endif