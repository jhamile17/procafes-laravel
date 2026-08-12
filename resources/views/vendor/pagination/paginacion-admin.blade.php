@if ($paginator->hasPages())

    <nav aria-label="Paginación administrativa">

        <ul class="pagination pagination-sm mb-0">

            {{-- Anterior --}}
            @if ($paginator->onFirstPage())

                <li class="page-item disabled">

                    <span class="page-link">
                        <i class="bi bi-chevron-left"></i>
                        <span class="d-none d-md-inline">
                            Anterior
                        </span>
                    </span>

                </li>

            @else

                <li class="page-item">

                    <a
                        href="{{ $paginator->previousPageUrl() }}"
                        class="page-link"
                        rel="prev"
                        aria-label="Página anterior"
                    >
                        <i class="bi bi-chevron-left"></i>

                        <span class="d-none d-md-inline">
                            Anterior
                        </span>
                    </a>

                </li>

            @endif


            {{-- Números --}}
            @foreach ($elements as $element)

                @if (is_string($element))

                    <li class="page-item disabled">

                        <span class="page-link">
                            {{ $element }}
                        </span>

                    </li>

                @endif


                @if (is_array($element))

                    @foreach ($element as $page => $url)

                        @if ($page == $paginator->currentPage())

                            <li class="page-item active">

                                <span
                                    class="page-link"
                                    style="
                                        background:#D62828;
                                        border-color:#D62828;
                                    "
                                >
                                    {{ $page }}
                                </span>

                            </li>

                        @else

                            <li class="page-item">

                                <a
                                    href="{{ $url }}"
                                    class="page-link"
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

                <li class="page-item">

                    <a
                        href="{{ $paginator->nextPageUrl() }}"
                        class="page-link"
                        rel="next"
                        aria-label="Página siguiente"
                    >

                        <span class="d-none d-md-inline">
                            Siguiente
                        </span>

                        <i class="bi bi-chevron-right"></i>

                    </a>

                </li>

            @else

                <li class="page-item disabled">

                    <span class="page-link">

                        <span class="d-none d-md-inline">
                            Siguiente
                        </span>

                        <i class="bi bi-chevron-right"></i>

                    </span>

                </li>

            @endif

        </ul>

    </nav>

@endif